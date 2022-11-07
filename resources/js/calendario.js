!(function() {
    /**
     * Função para traduzir o nome dos meses
     * @param  {string} titulo 
     * @return {string} 
     */
    function traduzirMes(titulo) {
        if (titulo.includes('January')) {
            return 'Janeiro'

        } else if (titulo.includes('February')) {
            return 'Fevereiro'
        
        } else if (titulo.includes('March')) {
            return 'Março'
        
        } else if (titulo.includes('April')) {
            return 'Abril'
        
        } else if (titulo.includes('May')) {
            return 'Maio'
        
        } else if (titulo.includes('June')) {
            return 'Junho'
        
        } else if (titulo.includes('July')) {
            return 'Julho'
        
        } else if (titulo.includes('August')) {
            return 'Agosto'
        
        } else if (titulo.includes('September')) {
            return 'Setembro'
        
        } else if (titulo.includes('October')) {
            return 'Outubro'
        
        } else if (titulo.includes('November')) {
            return 'Novembro'
        
        } else if (titulo.includes('December')) {
            return 'Dezembro'

        }
    }

    /**
     * Função para traduiz o nome dos dias
     * @param  {string} dia
     * @return {string}
     */
    function traduzirDia(dia) {
        switch (dia) {
            case 'Sun':
                return 'Dom'

            case 'Mon':
                return 'Seg'                

            case 'Tue':
                return 'Ter'
                
            case 'Wed':
                return 'Qua'
                
            case 'Thu':
                return 'Qui'
            
            case 'Fri':
                return 'Sex';
                
            case 'Sat':
                return 'Sab';
        }
    }
    
    var today = moment();
    
    function Calendar(selector, events) {
        this.el = document.querySelector(selector);
        this.events = events;
        this.current = moment().date(1);
        this.draw();
        var current = document.querySelector(".today");
        if (current) {
            var self = this;
            window.setTimeout(function() {
                self.openDay(current);
            }, 500);
        }
        //this.drawLegend();
    }
  
    Calendar.prototype.draw = function() {
        //Create Header
        this.drawHeader();
    
        //Draw Month
        this.drawMonth();
    
        //this.drawLegend();
    };
  
    Calendar.prototype.drawHeader = function() {
        var self = this;
        const titulo = this.current.format('MMMM YYYY');

        if (!this.header) {
            //Create the header elements
            this.header = createElement("div", "header");
            this.header.className = "header";
    
            this.title = createElement("h1");
    
            var right = createElement("div", "right");
            right.addEventListener("click", function() {
                self.nextMonth();
            });
    
            var left = createElement("div", "left");
            left.addEventListener("click", function() {
                self.prevMonth();
            });
    
            //Append the Elements
            this.header.appendChild(this.title);
            this.header.appendChild(right);
            this.header.appendChild(left);
            this.el.appendChild(this.header);
        }
    
        this.title.innerHTML = titulo.replace(this.current.format('MMMM'), traduzirMes(titulo));
    };
  
    Calendar.prototype.drawMonth = function() {
        var self = this;
    
        this.events.forEach(function(ev) {
            ev.date = moment(ev.dat_evento, "YYYY-MM-DD hh:mm:ss");
        });
    
        if (this.month) {
            this.oldMonth = this.month;
            this.oldMonth.className = "month out " + (self.next ? "next" : "prev");
            this.oldMonth.addEventListener("webkitAnimationEnd", function() {
                self.oldMonth.parentNode.removeChild(self.oldMonth);
                self.month = createElement("div", "month");
                self.backFill();
                self.currentMonth();
                self.fowardFill();
                self.el.appendChild(self.month);
                window.setTimeout(function() {
                    self.month.className = "month in " + (self.next ? "next" : "prev");
                }, 16);
            });
        } else {
            this.month = createElement("div", "month");
            this.el.appendChild(this.month);
            this.backFill();
            this.currentMonth();
            this.fowardFill();
            this.month.className = "month new";
        }
    };
  
    Calendar.prototype.backFill = function() {
        var clone = this.current.clone();
        var dayOfWeek = clone.day();
    
        if (!dayOfWeek) {
            return;
        }
    
        clone.subtract("days", dayOfWeek + 1);
    
        for (var i = dayOfWeek; i > 0; i--) {
            this.drawDay(clone.add("days", 1));
        }
    };
  
    Calendar.prototype.fowardFill = function() {
        var clone = this.current
            .clone()
            .add("months", 1)
            .subtract("days", 1);
        var dayOfWeek = clone.day();
    
        if (dayOfWeek === 6) {
            return;
        }
    
        for (var i = dayOfWeek; i < 6; i++) {
            this.drawDay(clone.add("days", 1));
        }
    };
  
    Calendar.prototype.currentMonth = function() {
        var clone = this.current.clone();
    
        while (clone.month() === this.current.month()) {
            this.drawDay(clone);
            clone.add("days", 1);
        }
    };
  
    Calendar.prototype.getWeek = function(day) {
        if (!this.week || day.day() === 0) {
            this.week = createElement("div", "week");
            this.month.appendChild(this.week);
        }
    };
  
    Calendar.prototype.drawDay = function(day) {
        var self = this;
        this.getWeek(day);
        let dia = day.format('ddd');
        dia = traduzirDia(dia);
    
        //Outer Day
        var outer = createElement("div", this.getDayClass(day));
        outer.addEventListener("click", function() {
            self.openDay(this);
        });
    
        //Day Name
        var name = createElement("div", "day-name", dia);
    
        //Day Number
        var number = createElement("div", "day-number", day.format("DD"));
    
        //Events
        var events = createElement("div", "day-events");
        this.drawEvents(day, events);
    
        outer.appendChild(name);
        outer.appendChild(number);
        outer.appendChild(events);
        this.week.appendChild(outer);
    };
  
    Calendar.prototype.drawEvents = function(day, element) {
        if (day.month() === this.current.month()) {
            var todaysEvents = this.events.reduce(function(memo, ev) {
            if (ev.date.isSame(day, "day")) {
                memo.push(ev);
            }
            return memo;
            }, []);
    
            todaysEvents.forEach(function(ev) {
                // ev.color
                var evSpan = createElement("span", 'orange');
                element.appendChild(evSpan);
            });
        }
    };
  
    Calendar.prototype.getDayClass = function(day) {
        
        classes = ["day"];
        if (day.month() !== this.current.month()) {
            classes.push("other");
        } else if (today.isSame(day, "day")) {
            classes.push("today");
        }
        return classes.join(" ");
    };
  
    Calendar.prototype.openDay = function(el) {
        var details, arrow;
        var dayNumber =
            +el.querySelectorAll(".day-number")[0].innerText ||
            +el.querySelectorAll(".day-number")[0].textContent;
        var day = this.current.clone().date(dayNumber);
    
        var currentOpened = document.querySelector(".details");
    
        //Check to see if there is an open detais box on the current row
        if (currentOpened && currentOpened.parentNode === el.parentNode) {
            details = currentOpened;
            arrow = document.querySelector(".arrow");
        } else {
            //Close the open events on differnt week row
            //currentOpened && currentOpened.parentNode.removeChild(currentOpened);
            if (currentOpened) {
                currentOpened.addEventListener("webkitAnimationEnd", function() {
                    currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener("oanimationend", function() {
                    currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener("msAnimationEnd", function() {
                    currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.addEventListener("animationend", function() {
                    currentOpened.parentNode.removeChild(currentOpened);
                });
                currentOpened.className = "details out";
            }
    
            //Create the Details Container
            details = createElement("div", "details in");
    
            //Create the arrow
            var arrow = createElement("div", "arrow");
            arrow.classList.add("d-none");
            arrow.classList.add("d-sm-block");
    
            //Create the event wrapper
    
            details.appendChild(arrow);
            el.parentNode.appendChild(details);
        }
    
        var todaysEvents = this.events.reduce(function(memo, ev) {
            if (ev.date.isSame(day, "day")) {
                memo.push(ev);
            }
            return memo;
        }, []);
    
        this.renderEvents(todaysEvents, details);
    
        arrow.style.left = el.offsetLeft - el.parentNode.offsetLeft + 27 + "px";
    };
  
    Calendar.prototype.renderEvents = function(events, ele) {
        //Remove any events in the current details element
        var currentWrapper = ele.querySelector(".events");
        var wrapper = createElement(
            "div",
            "events in" + (currentWrapper ? " new" : "")
        );
    
        events.forEach(function(ev) {
            var div = createElement("div", "event");
            var square = createElement("div", "event-category " + ev.color);
            var data = new Date(ev.dat_evento);

            data = `${(data.getDate()<10?'0':'') + data.getDate()}/${(data.getMonth()<9?'0':'') + (data.getMonth() + 1)} às ${data.getHours()}:${(data.getMinutes()<10?'0':'') + data.getMinutes()}`;

            var span = createElement("span", "", `${data} - ${ev.dsc_evento}`);
    
            div.appendChild(square);
            div.appendChild(span);
            wrapper.appendChild(div);
        });
    
        if (!events.length) {
            var div = createElement("div", "event empty");
            var span = createElement("span", "", "Sem Eventos");
    
            div.appendChild(span);
            wrapper.appendChild(div);
        }
    
        if (currentWrapper) {
            currentWrapper.className = "events out";
            currentWrapper.addEventListener("webkitAnimationEnd", function() {
                currentWrapper.parentNode.removeChild(currentWrapper);
                ele.appendChild(wrapper);
            });
            currentWrapper.addEventListener("oanimationend", function() {
                currentWrapper.parentNode.removeChild(currentWrapper);
                ele.appendChild(wrapper);
            });
            currentWrapper.addEventListener("msAnimationEnd", function() {
                currentWrapper.parentNode.removeChild(currentWrapper);
                ele.appendChild(wrapper);
            });
            currentWrapper.addEventListener("animationend", function() {
                currentWrapper.parentNode.removeChild(currentWrapper);
                ele.appendChild(wrapper);
            });
        } else {
            ele.appendChild(wrapper);
        }
    };
  
    Calendar.prototype.drawLegend = function() {
        var legend = createElement("div", "legend");
        var calendars = this.events
            .map(function(e) {
            return e.calendar + "|" + e.color;
            })
            .reduce(function(memo, e) {
            if (memo.indexOf(e) === -1) {
                memo.push(e);
            }
            return memo;
            }, [])
            .forEach(function(e) {
            var parts = e.split("|");
            var entry = createElement("span", "entry " + parts[1], parts[0]);
            legend.appendChild(entry);
            });
        this.el.appendChild(legend);
    };
  
    Calendar.prototype.nextMonth = function() {
        this.current.add("months", 1);
        this.next = true;
        this.draw();
    };
  
    Calendar.prototype.prevMonth = function() {
        this.current.subtract("months", 1);
        this.next = false;
        this.draw();
    };
  
    window.Calendar = Calendar;
  
    function createElement(tagName, className, innerText) {
        var ele = document.createElement(tagName);
        if (className) {
            ele.className = className;
        }
        if (innerText) {
            ele.innderText = ele.textContent = innerText;
        }
        return ele;
    }
})();

function getCookie(name) {
    let cookie = {};

    document.cookie.split(';').forEach(function(el) {
        let [k,v] = el.split('=');
        cookie[k.trim()] = v;
    })

    return cookie[name];
}

!(function() {
    // NOME DO COKIE (mdc-calendario)
    // OBTER ESSE COKIE VIA JS E COLOCAR NESSE ARRAY
    // OBS: Traduzir o formato do cookie que esta em JSON string

    // var data = [
    //     {
    //         dsc_evento: "Festa Cultural",
    //         dat_evento: "2022-10-10 18:00:00"
    //     },
    //     {
    //         dsc_evento: "Lunch Meeting w/ Mark jfddfjfdsjhfdsfsdfsdjhdfsdfsfsdsdf",
    //         dat_evento: "2022-10-24 16:03:00"
    //     },
    //     {
    //         dsc_evento: "Lunch Meeting w/ Mark",
    //         dat_evento: "2022-10-26 16:10:00"
    //     }
    // ];
    var data = JSON.parse(decodeURIComponent(getCookie("mdc-calendario")));

    function addDate(ev) {}
  
    var calendar = new Calendar("#calendar", data);
})();
  