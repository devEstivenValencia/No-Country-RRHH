(function(){

    class Calendary {
        constructor(id){        
            this.calendarElem = document.getElementById(id);
            this.MONTHS = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

            this.click = false;
            this.clickDate = false;
            this.initialDate;
            this.finalDate;
            // Obtener la fecha actual
            let date = new Date();
            this.currentDate = new Date(date.getFullYear(),date.getMonth(),date.getDate());
            
            this.createInitialData(date);
            this.createBaseHTML();
            this.createHTMLElement();

            //add even click to move forward and backward
            document.addEventListener("click",e=>{
                if(e.target.matches("#calendar-next-button")||e.target.matches("#calendar-next-button *")){
                    //console.log(new Date(this.nextYear,this.nextMonth,1));
                    if(!this.click){
                        this.calendarElem.querySelector("table.current-table")?.classList.add("anim-next");
                        this.calendarElem.querySelector("table.side-table")?.classList.add("anim-next");
                        this.calendarElem.querySelector(".current-month")?.classList.add("anim-next");
                        this.calendarElem.querySelector(".side-month")?.classList.add("anim-next");
                        this.click = true;
                    }
                }else if(e.target.matches("#calendar-prev-button")||e.target.matches("#calendar-prev-button *")){
                    //console.log(new Date(this.prevYear,this.prevMonth,1));
                    if(!this.click){
                        this.calendarElem.querySelector("table.current-table")?.classList.add("anim-prev");
                        this.calendarElem.querySelector("table.back-table")?.classList.add("anim-prev");
                        this.calendarElem.querySelector(".current-month")?.classList.add("anim-prev");
                        this.calendarElem.querySelector(".back-month")?.classList.add("anim-prev");
                        this.click = true;
                    }
                }else if(e.target.matches("table td.day-of-month div")){
                    this.action(e.target.parentElement.getAttribute("value"),e.target.parentElement.getAttribute("date"));
                }else if(e.target.matches("table td.day-of-month")){
                    this.action(e.target.getAttribute("value"),e.target.getAttribute("date"));
                }else if(e.target.matches("#reset-date")){
                    this.clickDate = false;
                    document.getElementById("initial-date")?.setAttribute("value","");
                    document.getElementById("initial-date")?.setAttribute("date","");
                    document.getElementById("final-date")?.setAttribute("value","");
                    document.getElementById("final-date")?.setAttribute("date","");
                    this.initialDate = null;
                    this.finalDate = null;
                    this.createInitialData(new Date(this.year,this.month,1));
                    this.createHTMLElement();
                }
            });
            //add event for animation end
            document.addEventListener("animationend",e=>{
                if(this.click){
                    if(e.target.matches("table.anim-next")){
                        this.click = false;
                        this.createInitialData(new Date(this.nextYear,this.nextMonth,1));
                        this.createHTMLElement();
                    }else if(e.target.matches("table.anim-prev")){
                        this.click = false;
                        this.createInitialData(new Date(this.prevYear,this.prevMonth,1));
                        this.createHTMLElement();
                    }
                }
            });
        }

        createInitialData(date){
            // Obtener el mes y año para la fecha dada
            this.month = date.getMonth(); // Los meses en JavaScript comienzan en 0
            this.year = date.getFullYear();

            //get previous year and month
            this.prevYear = this.year;
            this.prevMonth = this.month - 1;
            if(this.prevMonth < 0){
                this.prevMonth = 11;
                this.prevYear = this.year - 1;
            }

            //get next year and month
            this.nextYear = this.year;
            this.nextMonth = this.month + 1;
            if(this.nextMonth > 11){
                this.nextMonth = 0;
                this.nextYear = this.year + 1;
            }

        }
        createBaseHTML(){
            this.calendarElem.innerHTML = "";
            //create date input and reset button
            let dateContainer = document.createElement("div");
            dateContainer.classList.add("date-container");
            this.calendarElem.appendChild(dateContainer);

            let initialDateInput = document.createElement("input");
            initialDateInput.setAttribute("type","text");
            initialDateInput.setAttribute("name","initial-date");
            initialDateInput.setAttribute("id","initial-date");
            initialDateInput.setAttribute("placeholder","check-in");
            initialDateInput.setAttribute("readonly","true");
            dateContainer.appendChild(initialDateInput);
            
            let finalDateInput = document.createElement("input");
            finalDateInput.setAttribute("type","text");
            finalDateInput.setAttribute("name","final-date");
            finalDateInput.setAttribute("id","final-date");
            finalDateInput.setAttribute("placeholder","check-out");
            finalDateInput.setAttribute("readonly","true");
            dateContainer.appendChild(finalDateInput);

            let resetButton = document.createElement("button");
            resetButton.setAttribute("type","button");
            resetButton.setAttribute("id","reset-date");
            resetButton.innerHTML = "borrar fecha";
            dateContainer.appendChild(resetButton);

            //create button-constainer
            let buttonContainer = document.createElement("div");
            buttonContainer.classList.add("button-container");
            this.calendarElem.appendChild(buttonContainer);

            //create month-container for calendar
            this.monthContainer = document.createElement("div");
            this.monthContainer.classList.add("month-container");

            //create next and prev. buttons
            let buttonNext = document.createElement("button");
            buttonNext.setAttribute("id","calendar-next-button");
            buttonNext.setAttribute("type","button");
            buttonNext.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>';
            
            let buttonPrev = document.createElement("button");
            buttonPrev.setAttribute("id","calendar-prev-button");
            buttonPrev.setAttribute("type","button");
            buttonPrev.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>';

            //add buttons and month-container to the button-container
            buttonContainer.appendChild(buttonPrev);
            buttonContainer.appendChild(this.monthContainer);
            buttonContainer.appendChild(buttonNext);

            //create and add table-container element
            this.tableContainer = document.createElement("div");
            this.tableContainer.classList.add("table-container");
            this.calendarElem.appendChild(this.tableContainer);
        }
        createHTMLElement(){
            this.monthContainer.innerHTML = "";
            this.tableContainer.innerHTML = "";

            this.createMonth(this.monthContainer,-1,this.prevYear,this.prevMonth);
            this.createMonth(this.monthContainer,0,this.year,this.month);
            this.createMonth(this.monthContainer,1,this.nextYear,this.nextMonth);

            new CalendaryTable(this.currentDate,new Date(this.year,this.month,1),this.tableContainer,this.initialDate,this.finalDate);
        }
        createMonth(monthContainer,position,year,month){
            let classToAdd = 'current';
            if(position < 0) classToAdd = 'back';
            if(position > 0) classToAdd = 'side';
            
            let divMonth = document.createElement("div");
            divMonth.classList.add(classToAdd + "-month");
            divMonth.innerHTML = this.MONTHS[month] + " de " + year;
            monthContainer.appendChild(divMonth);
        }
        action(value,dateString){
            let newDate = new Date(dateString);
            if(!this.clickDate){
                this.clickDate = true;
                document.getElementById("initial-date")?.setAttribute("value",value);
                document.getElementById("initial-date")?.setAttribute("date",newDate);
                this.initialDate = newDate;
            }else{
                let oldDate = this.initialDate;

                if(newDate > oldDate){
                    document.getElementById("final-date")?.setAttribute("value",value);
                    document.getElementById("final-date")?.setAttribute("date",newDate);
                    this.finalDate = newDate;
                }else{
                    alert("final date must be greater than initial date")
                }
            }
            this.createInitialData(new Date(this.year,this.month,1));
            this.createHTMLElement();
        }
    }

    class CalendaryTable {
        constructor(currentDate, dateToDraw, tableContainer, initialDate, finalDate){
            this.DAYS = ["do.","lu.","ma.","mi.","ju.","vi.","sa."];
            this.currentDate = currentDate;
            this.tableContainer = tableContainer;
            this.initialDate = initialDate;
            this.finalDate = finalDate;

            //create month
            this.createInitialData(dateToDraw);
            this.createHTML(0);

            //define prev and next month
            this.dateToDrawPrev = new Date(this.prevYear,this.prevMonth,1);
            this.dateToDrawNext = new Date(this.nextYear,this.nextMonth,1);

            //create prev month
            this.createInitialData(this.dateToDrawPrev);
            this.createHTML(-1);

            //create next month
            this.createInitialData(this.dateToDrawNext);
            this.createHTML(1);
        }
        createInitialData(date){
            // Obtener el mes y año para la fecha dada
            this.month = date.getMonth(); // Los meses en JavaScript comienzan en 0
            this.year = date.getFullYear();

            //get previous year and month
            this.prevYear = this.year;
            this.prevMonth = this.month - 1;
            if(this.prevMonth < 0){
                this.prevMonth = 11;
                this.prevYear = this.year - 1;
            }

            //get next year and month
            this.nextYear = this.year;
            this.nextMonth = this.month + 1;
            if(this.nextMonth > 11){
                this.nextMonth = 0;
                this.nextYear = this.year + 1;
            }
        }
        createHTML(position){
            let classToAdd = 'current';
            if(position < 0) classToAdd = 'back';
            if(position > 0) classToAdd = 'side';

            let table = document.createElement("table");
            table.classList.add(classToAdd + "-table");
            this.tableContainer.appendChild(table);

            //create header with days name
            let tHead = document.createElement("thead");
            table.appendChild(tHead);

            let trHead = document.createElement("tr");
            tHead.appendChild(trHead);

            this.DAYS.forEach(d => {
                let th = document.createElement("th");
                let div = document.createElement("div");
                div.innerHTML = d;
                th.appendChild(div);
                trHead.appendChild(th);
            });

            //crete a list with numerc days
            let printDays = this.createPrintableDays(this.year,this.month,this.nextYear,this.nextMonth);
            let tBody = this.createtBody(printDays);

            table.appendChild(tBody);
        }        
        createPrintableDays(year,month,nextYear,nextMonth){
            let firstWeekDayOfMonth = new Date(year,month,1).getDay();
            let lastDayOfMonth = new Date(nextYear,nextMonth,0).getDate();

            let printDays = [];//a list of weeks
            let week = [];
            let seven = 0;

            //print last days of previous month if week do not start with first day of month
            if(firstWeekDayOfMonth !== 0){
                let lastDayOfPrevMonth = new Date(year,month,0).getDate();
                let lastDayOfWeekPrevMonth = new Date(year,month,0).getDay();
                let firstPrintDay = lastDayOfPrevMonth - lastDayOfWeekPrevMonth;
                
                for(let i = firstPrintDay; i <= lastDayOfPrevMonth ; i++){
                    week.push({day: i,type: 0});
                    seven++;
                }
            }
            //print days of specific month
            for(let i = 1; i<=lastDayOfMonth; i++){
                let type = 1;
                let dateToTest = new Date(year,month,i);
                if(this.currentDate <= dateToTest){
                    type = 2;
                }
                week.push({day: i,type});
                seven++;
                if(seven === 7){
                    printDays.push(week);
                    week = [];
                    seven = 0;
                }
            }
            //complete the week with days if this is not full
            if(seven !== 0){
                let rest = 7 - seven;
                for(let i = 1 ; i <= rest ; i++)
                    week.push({day: i,type: 0});
                printDays.push(week);
            }
            return printDays;
        }
        createtBody(printDays){
            let tBody = document.createElement("tbody");

            for(let i = 0; i < printDays.length; i++){
                let row = document.createElement("tr");
                for(let j = 0; j < printDays[i].length; j++){
                    let dayToPrint = printDays[i][j];
                    let td = document.createElement("td");
                    let div = document.createElement("div");
                    if(dayToPrint.type === 1){
                        td.classList.add("day-of-month-invalid");
                        div.innerHTML = dayToPrint.day;
                    }
                    if(dayToPrint.type === 2){
                        td.classList.add("day-of-month");
                        td.setAttribute("value",this.addCero(dayToPrint.day)+"/"+this.addCero(this.month+1)+"/"+this.year);
                        let date = new Date(this.year, this.month, dayToPrint.day);
                        td.setAttribute("date",date);
                        div.innerHTML = dayToPrint.day;
                        if(this?.initialDate?.getTime() === date.getTime()){
                            td.classList.add("initial-day");
                        }
                        if(this?.initialDate?.getTime() === date.getTime() && this.finalDate){
                            td.classList.add("is-prev");
                        }
                        if(this?.finalDate?.getTime() === date.getTime()){
                            td.classList.add("final-day");
                        }
                        if(this?.initialDate < date && this?.finalDate > date){
                            td.classList.add("selected-day");
                        }
                    }
                    td.appendChild(div);
                    row.appendChild(td);
                }
                tBody.appendChild(row);
            }
            
            return tBody;
        }
        addCero(number){
            if(number < 10){
                return "0" + number;
            }
            return number;
        }
    }
    
    let obj = new Calendary("calendario");
})();