import firebase from "firebase/app";
import "firebase/database";

let config = {
    apiKey: "AIzaSyBhUSxP2rZWBAI9o6W7_PvQ1ui4kRsolTw",
    authDomain: "fluentalert.firebaseapp.com",
    databaseURL: "https://fluentalert-default-rtdb.firebaseio.com",
    projectId: "fluentalert",
    storageBucket: "fluentalert.appspot.com",
    messagingSenderId: "380397228707",
    appId: "1:380397228707:web:5ee9fad7b4119b544ca516"
};

firebase.initializeApp(config);

export default firebase.database();
