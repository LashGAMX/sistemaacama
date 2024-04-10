$(document).ready(function () {

    
    $('#btnEjecutar').click(function () {
        convertirDatos()
    });


});
var jtexto
const monthMapping = {
    'JAN': '01', 'FEB': '02', 'MAR': '03', 'APR': '04', 'MAY': '05', 'JUN': '06',
    'JUL': '07', 'AUG': '08', 'SEP': '09', 'OCT': '10', 'NOV': '11', 'DEC': '12'
  };
var dateStr = '17-JUN-87';
var [day, monthAbbr, year] = dateStr.split('-');
var month = monthMapping[monthAbbr];
var formattedYear = year.length === 2 ? '19' + year : year;
var newDateStr = `${day}/${month}/${formattedYear}`;

function convertirDatos()
{
    let area = document.getElementById("jsonOrdenado")
    let jsonString  = $("#jsonTexto").val() // jquery
    let temp = ''
    let tempCont = 0
    let space = ""
    let aux = ""
    let aux2 = ""
    jtexto = JSON.parse(jsonString)

    for (let i = 0; i < jtexto.length; i++) {
        temp += '   '+jtexto[i].employee_id+''+jtexto[i].first_name
        aux = ""+jtexto[i].employee_id
        tempCont = 23 - (aux.length + jtexto[i].first_name.length)
        space = ' '.repeat(tempCont)
        temp += ''+space+''+jtexto[i].last_name

        tempCont = 25 - (jtexto[i].last_name.length)
        space = ' '.repeat(tempCont)
        temp += ''+space+''+jtexto[i].email

        tempCont = 25 - (jtexto[i].email.length)
        space = ' '.repeat(tempCont)
        temp += ''+space+''+jtexto[i].phone_number

        tempCont = 20 - (jtexto[i].phone_number.length)

        dateStr = ""+jtexto[i].hire_date 
        let [day, monthAbbr, year] = dateStr.split('-'); 
        month = monthMapping[monthAbbr];
        formattedYear = year.length === 2 ? '19' + year : year;
        let anioDosDigitos = year.slice(-2);
        newDateStr = `${day}/${month}/${anioDosDigitos}`;

        space = ' '.repeat(tempCont)
        temp += ''+space+''+newDateStr+''+jtexto[i].job_id
        
        tempCont = 18 - (newDateStr.length + jtexto[i].job_id.length)
        space = ' '.repeat(tempCont)
        temp += ''+space+''+jtexto[i].salary

        aux = ""+jtexto[i].salary
        tempCont = 8 - (aux.length)
        space = ' '.repeat(tempCont)
        aux = "NULL"
        aux2 = "NULL"
        if (jtexto[i].commission_pct != null) {
            aux = jtexto[i].commission_pct
        }
        if (jtexto[i].manager_id != null) {
            aux2 = jtexto[i].manager_id
        }
        temp += ''+space+''+aux + "" + aux2

        tempCont = 12 - ((""+aux).length + (""+aux2).length)
        space = ' '.repeat(tempCont)
        aux = "NULL"
        if (jtexto[i].department_id != null) {
            aux = jtexto[i].department_id
        }
        temp += ''+space+''+aux
        
        
        temp += '\n'
    }
    area.innerHTML = temp

}