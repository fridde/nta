import {Controller} from '@hotwired/stimulus';
import Dialog from '@stimulus-components/dialog'
import Req from "Req";
import Toasthandler from "Toasthandler";

const d = document;

/* stimulusFetch: 'lazy' */
export default class extends Dialog {
    userFields = ['FirstName', 'LastName', 'Mail'];

    static targets = ["input", 'saveButton'];

    static values = {
        userId: String,
        requestSchool: String
    };

    close() {
        super.close();
    }

    openNewUserDialog() {
        const row = d.querySelectorAll('tr[data-id="new"]').item(0);
        const newerRow = row.cloneNode(true);
        row.after(newerRow);
        this.userIdValue = 'new_' + Math.random().toString(36).substring(2, 5);
        row.dataset.id = this.userIdValue;
        this.syncRowWithDialog(row, 'r>d');
        this.saveButtonTarget.toggleAttribute('disabled', true);
        super.open();
    }

    editUser(event) {
        const row = event.target.closest('tr');

        this.syncRowWithDialog(row, 'r>d');

        this.userIdValue = row.dataset.id;
        super.open();
    }

    save() {

        const row = d.querySelector('tr[data-id="' + this.userIdValue + '"]');
        this.syncRowWithDialog(row, 'd>r');

        const user = this.getUserFromDialog();
        user.id = this.userIdValue;
        this.saveUserToDb(user);
        this.close();
    }

    syncRowWithDialog(row, direction) {

        this.userFields.forEach(fieldName => {
            let userCell = this.getCellFromRow(row, fieldName);
            let input = this.inputTargets.find(input => input.name === fieldName);

            if (direction === 'r>d') {  // row to dialog
                input.value = userCell.textContent;
            } else if (direction === 'd>r') { // dialog to row
                userCell.textContent = input.value;
            }
        });
    }

    getCellFromRow(row, fieldName) {
        return Array.from(row.children).find(td => td.dataset.field === fieldName);
    }

    getUserFromDialog() {
        const user = {};

        this.inputTargets.forEach(input => {
            user[input.name] = input.value;
        });

        return user;
    }

    saveUserToDb(user) {

        let url = '/api/user/' + user.id;
        let successHandler = Toasthandler.showSuccessfulToast;
        if (user.id.startsWith('new')) {
            url = '/api/create/user';
            user.School = this.requestSchoolValue;
            successHandler = this.showNewUserRow;
        }

        const req = Req.create()
            .setUrl(url)
            .addToData('user_data', user)
            .setSuccessHandler(successHandler)
            .setErrorHandler(Toasthandler.showErrorToast)
            .send();
    }

    showNewUserRow(data) {
        const row = d.querySelector('tr[data-id="' + data["temp_id"] + '"]');
        row.dataset.id = data["user_id"];
        row.classList.remove("d-none");
        row.classList.add("text-bg-success");

        Toasthandler.showSuccessfulToast('Användare skapades!');
    }

    checkMailField(event) {
        this.saveButtonTarget.toggleAttribute('disabled', !event.target.checkValidity());
    }
}