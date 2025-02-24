import moment from 'moment';
import JSZip from 'jszip';
import Req from 'Req';
import saveAs from 'file-saver';
// import * as t from 'seedrandom';
// import * as tf from '@tensorflow/tfjs';
// import tf from 'https://cdn.jsdelivr.net/npm/@tensorflow/tfjs/dist/tf.min.js';

const MODEL_URL = 'https://tfhub.dev/google/tfjs-model/imagenet/mobilenet_v3_small_100_224/feature_vector/5/default/1';
const MOBILE_NET_INPUT_SIZE = 224;

const photoPreview = document.getElementById('photo-preview');

const trainingDataInputs = [];
const trainingDataOutputs = [];

let timestampedFiles = [];

let ARTICLE_NAMES;

//

function renamePhotosAndSave(photoTimesData) {
    console.log(photoTimesData.length + ' rows downloaded');
    const zip = new JSZip();
    const lastKey = photoTimesData.length - 1;

    photoTimesData.forEach((photoTimeData, index) => {
        let givenTime = moment(photoTimeData["Timestamp"]);
        timestampedFiles.forEach((file) => {
            let base = file.name.split('.').shift();
            let parts = base.split('_');
            if (parts.length < 3) {
                return;
            }
            let fileTime = moment([parts[1], parts[2]].join(' '), 'YYYYMMDD hhmmss');
            let isAfter = fileTime.isAfter(givenTime);
            let isBefore = true;
            if (index < lastKey) {
                isBefore = fileTime.isBefore(moment(photoTimesData[index + 1]["Timestamp"]));
            }
            if (isAfter && isBefore) {
                let random = crypto.randomUUID().slice(0, 3);
                let newFileName = photoTimeData["Artikel"] + '_' + random + '.jpg';
                zip.file(newFileName, file);
            }
        });
    });

    zip.generateAsync({type: "blob"})
        .then((blob) => {
            try {
                saveAs(blob, 'renamed_photos.zip')
            } catch (e) {
                console.error(e.message);
            }
        });
}

document.getElementById('rename-images').addEventListener('change', (event) => {

    timestampedFiles = Array.from(event.target.files);

    return Req.create()
        .setUrl('/api/get-photo-dates')
        .setMethod('GET')
        .setSuccessHandler(renamePhotosAndSave)
        .send();

});

document.getElementById('recalculate-model').addEventListener('change', async (event) => {
    console.log('recalculate model');
    const mobilenet = await tf.loadGraphModel(MODEL_URL, {fromTFHub: true});

    tf.tidy(() => mobilenet.predict(tf.zeros([1, MOBILE_NET_INPUT_SIZE, MOBILE_NET_INPUT_SIZE, 3])));

    let photoFiles = Array.from(event.target.files);
    ARTICLE_NAMES = getAvailableArticlesFromImages(photoFiles);

    let model = tf.sequential();
    let dense = tf.layers.dense({inputShape: [1024], units: 128, activation: 'relu'});

    model.add(dense);  // For debugging purposes
    model.add(tf.layers.dense({units: ARTICLE_NAMES.length, activation: 'softmax'}));

    // model.summary();

    model.compile({
        optimizer: 'adam', // changes the learning rate over time which is useful
        loss: 'categoricalCrossentropy',
        metrics: ['accuracy'] // records accuracy
    });

    photoFiles.forEach((photoFile) => {

        let fileReader = new FileReader();

        fileReader.onload = (event) => {
            console.info("Loaded...")
            photoPreview.setAttribute('src', event.target.result);
            let imageAsTensor = tf.browser.fromPixels(photoPreview);
            let resizedTensorFrame = tf.image.resizeBilinear(imageAsTensor,
                [MOBILE_NET_INPUT_SIZE, MOBILE_NET_INPUT_SIZE],
                true
            );
            let normalizedTensorFrame = resizedTensorFrame.div(255);
            let imageFeatures = mobilenet.predict(normalizedTensorFrame.expandDims()).squeeze();
            trainingDataInputs.push(imageFeatures);
            trainingDataOutputs.push(ARTICLE_NAMES.indexOf(getArticleFromFile(photoFile)));
        };
        fileReader.readAsDataURL(photoFile);

    });
});


function getArticleFromFile(file) {
    const base = file.name.split('.').shift();

    return base.split('_').shift();
}

function getAvailableArticlesFromImages(imageFiles) {
    const articles = new Set(imageFiles.map((file) => getArticleFromFile(file)));

    return [...articles];
}
