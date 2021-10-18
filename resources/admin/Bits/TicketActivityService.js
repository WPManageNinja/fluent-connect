import firebase from "./firebase";

const db = firebase.ref("/ticket_activity");

class TicketActivityService {

    getAgents(ticketId) {
        return db.child('ticket_' + ticketId);
    }

    addAgent(ticketId, personId, data) {
        const obj = {};
        obj[personId] = data;
        return db.child('ticket_' + ticketId).update(obj);
    }

    removeAgent(ticketId, personId) {
        return db.child('ticket_' + ticketId).child(personId).remove();
    }

    ticketChanged(ticketId, dataType, data) {
        return db.child('ticket_update_' + ticketId).update({
            type: dataType,
            data: data
        });
    }

    getTicketUpdates(ticketId) {
        return db.child('ticket_update_' + ticketId);
    }

}

export default new TicketActivityService();
