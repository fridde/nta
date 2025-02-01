import {Controller} from '@hotwired/stimulus';
import Req from "../utils/Req.js";
import Toasthandler from "../utils/Toasthandler.js";

/* stimulusFetch: 'lazy' */
export default class extends Controller {

    static targets = ["updateType", "boxes"];

    update() {

        Req.create()
            .setUrl('/api/batch/box-status')
            .addToData('updateType', this.updateTypeTarget.value)
            .addToData('boxes', this.boxesTarget.value)
            .setSuccessHandler(Toasthandler.showSuccessfulToast)
            .setErrorHandler(Toasthandler.showErrorToast)
            .send();

    }

}