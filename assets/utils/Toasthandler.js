'use strict';

import {Toast} from "bootstrap";

const d = document;

class Toasthandler {

    static showSuccessfulToast(response) {
        let text;
        if(typeof response === "string"){
            text = response;
        }
        if(typeof response === "object" && response["success"]){
            text = "";
        }
        Toasthandler.showToast(text, true);
    }

    static showErrorToast(error) {
        const toastText = JSON.parse(error.message)["error"];
        Toasthandler.showToast(toastText, false);
    }

    static showToast(text, isSuccess) {
        const toastSelector = "update-toast-" + (isSuccess ? "success" : "error");
        const toastElement = d.getElementById(toastSelector);
        const toastInstance = Toast.getOrCreateInstance(toastElement);

        const toastBodyElement = d.querySelector(`#${toastSelector} .toast-body`);

        if(text.length > 0){
            toastBodyElement.textContent = text;
        }
        toastInstance.show();
    }

}

export default Toasthandler;