import * as tf from '@tensorflow/tfjs';
import {loadGraphModel} from '@tensorflow/tfjs-converter';

const TENSOR_SIZE = 224;
const model = await loadGraphModel('/image_model/model.json');
const CLASS_NAMES = JSON.parse(getById('class-names').dataset.names);

activateVideo();
warmUp();

function getById(id) {
    return document.getElementById(id);
}

getById('predict-image').addEventListener('click', async (event) => {
    predictLoop();
});

function calculateFeatures(imageTensor) {
    return tf.tidy(() => {
        let resizedTensorFrame = tf.image.resizeBilinear(imageTensor, [TENSOR_SIZE, TENSOR_SIZE], true);
        let normalizedTensorFrame = resizedTensorFrame.div(255);

        return normalizedTensorFrame.expandDims();
    });
}

function activateVideo(){
    const constraints = {
        video: true,
        width: 640,
        height: 480
    };
    navigator.mediaDevices.getUserMedia(constraints).then((stream) => {
        getById('webcam').srcObject = stream;
    });
}

function predictLoop() {
    let imageAsTensor = tf.browser.fromPixels(getById('webcam'));
    let features = calculateFeatures(imageAsTensor);

    const prediction = model.predict(features).squeeze();
    let highestIndex = prediction.argMax(-1).arraySync();
    getById('prediction-info').innerHTML = CLASS_NAMES[highestIndex] + " ; " + new Date();

    setTimeout(() => window.requestAnimationFrame(predictLoop), 1000);
}

function warmUp(){
    tf.tidy(() => {
        model.predict(tf.zeros([1, TENSOR_SIZE, TENSOR_SIZE, 3]));
    });
}


