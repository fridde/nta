import {Controller} from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ["topicSelector"];

    static values = {
        boxesleft: Object
    };

    updateTopicsAvailable(event) {
        const period = event.target.value;
        for (let option of this.topicSelectorTarget.options) {
            let available = this.boxesleftValue[period][option.value];
            if(available === 0){
                option.setAttribute('disabled', 'disabled');
            } else {
                option.removeAttribute('disabled');
            }
        }
    }
}
