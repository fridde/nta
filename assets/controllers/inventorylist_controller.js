import {Controller} from '@hotwired/stimulus';
import Req from "Req";

/* stimulusFetch: 'lazy' */
export default class extends Controller {

    static targets = ['table'];

    updateInventoryList(event) {
        Req.create()
            .setMethod('GET')
            .setUrl('/api/get-inventory-list/' + event.currentTarget.value)
            .setSuccessHandler(this.fillTable.bind(this))
            .send();
    }

    fillTable(data){
        const table = this.tableTarget;
        // for(const row of document.querySelectorAll('#inventory-body tr')) {
        for(const row of table.rows) {
            row.remove();
        }
        data['rows'].forEach((row)=>{
            let rowElement = table.insertRow(-1);
            rowElement.insertCell(0).innerHTML = row['desc'];
            rowElement.insertCell(1).innerHTML = row['amount'];
            rowElement.insertCell(2).innerHTML = row['comment'];
            rowElement.insertCell(3).innerHTML = row['counted'];
        });

    }
}