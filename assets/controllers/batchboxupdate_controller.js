import {Controller} from '@hotwired/stimulus';
import Req from "Req";
import Toasthandler from "Toasthandler";

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