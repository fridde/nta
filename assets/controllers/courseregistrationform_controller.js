import {Controller} from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ["topicSelector"];

    static values = {
        qualifications: Object
    };

    updateTopicsAvailable(event) {
        const user = event.target.value;
        for (let option of this.topicSelectorTarget.options) {
            let finished = this.qualificationsValue[user] ?? [];
            if(finished.includes(option.value)){
                option.setAttribute('disabled', 'disabled');
            } else {
                option.removeAttribute('disabled');
            }
        }
    }
}