Date.prototype.yyyymmdd = function() {
    var yyyy = this.getFullYear().toString();
    var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
    var dd  = this.getDate().toString();
    return yyyy +"-" +(mm[1]?mm:"0"+mm[0]) +"-" +(dd[1]?dd:"0"+dd[0]); // padding
};


Date.prototype.ddmm = function() {

    var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
    var dd  = this.getDate().toString();
    var day = giornoSettimana(this.getDay());
    return day+" "+ (dd[1]?dd:"0"+dd[0]) +"/" +(mm[1]?mm:"0"+mm[0]); // padding
};

$.datepicker.regional['it'] = {
    closeText: 'Chiudi', // set a close button text
    currentText: 'Oggi', // set today text
    monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',   'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'], // set month names
    monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'], // set short month names
    dayNames: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'], // set days names
    dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'], // set short day names
    dayNamesMin: ['Do','Lu','Ma','Me','Gio','Ve','Sa'], // set more short days names
    dateFormat: 'dd/mm/yy' // set format date
};

function giornoSettimana(num){
    var weekday = new Array(7);
    weekday[0]=  "Domenica";
    weekday[1] = "Lunedì";
    weekday[2] = "Martedì";
    weekday[3] = "Mercoledì";
    weekday[4] = "Giovedì";
    weekday[5] = "Venerdì";
    weekday[6] = "Sabato";

   return weekday[num];

}function giornoSettimanaAbbr(num){
    var weekday = new Array(7);
    weekday[0]=  "Dom";
    weekday[1] = "Lun";
    weekday[2] = "Mar";
    weekday[3] = "Mer";
    weekday[4] = "Gio";
    weekday[5] = "Ven";
    weekday[6] = "Sab";

   return weekday[num];
}

function toDate(dateStr) {
    var parts = dateStr.split("/");
    return new Date(parts[2], parts[1] - 1, parts[0]);
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

