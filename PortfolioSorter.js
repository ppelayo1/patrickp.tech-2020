//This function will set up links for the portfolio, handle ajax requests for new portfolio data, and sort them
class PortfolioSorter{
    constructor(){
        this.initVariables();
        this.setUpPortfolio();
        this.executeAjax();
    }
    
    //sets up the portfolio and performs and initial setups needed for the portfolio
    setUpPortfolio(){
        //For desktops, display the modal, set the links up for the iframe
        jQuery('.portfolioItem').on('click',this,this.modalHandler);

        //For mobile check open a new page
        jQuery('.portfolioItem').on('click',this.mobileEvents);

        //checks for iframe, if loading iframe hide the header/footer
        if(window.location !== window.parent.location){
            jQuery('#postHeader').hide();
            jQuery('#postFooter').hide();
        }
    }
    
    initVariables(){
        //Avoids button spamming, indicates the buttons are disabled
        this.buttonDisabled = false;
    }
    
    executeAjax(){
        let context = this;
        jQuery('.sortButton').click(function (e){
            let element = this;

            //Only perform the request if a differant button is pressed, and the specified time period has passed
            if(!context.buttonDisabled){  
                jQuery('#buttons').find('.buttonActive').removeClass('buttonActive').addClass('buttonInActive');
                jQuery(element).removeClass('buttonInActive').addClass('buttonActive');
                context.buttonDisabled = true;
                
                //renable the buttons after 0.4 seconds
                window.setTimeout(()=>{
                    context.buttonDisabled = false;
                },CONSTANTS.BUTTON_TIMOUT);

                jQuery.ajax({
                    type:'get',
                    url:myAjax.ajaxurl,
                    data:{action:CONSTANTS.AJAX_ACTION,ajax_term:element.dataset.ajax_term,nonce:jQuery(element).data('nonce')},
                    success:function(response){
                        response = JSON.parse(response);

                        if(response != false){
                            context.handlePortfolio(response);
                        }else{
                            console.log('error in the once');
                        }
                    }
                });
            }
        });
    }
    
    //This function will remove elements not on the page, and add in the new elements
    handlePortfolio(newElements){
        let currentElements = jQuery('.portfolioItem');
        let elements;

        //return 6 random elements
        elements = this.sixRandomElements(newElements);

        //remove the old elements
        this.removeOldElements(elements);

        //add in the new elements
        this.addNewElements(elements);
    }

    //checks the current portfolio against the element to see if it is already present
    checkIfOnPorfolio(newElement){
        let currentElements = jQuery('.portfolioItem');
        let returnVal = false;

        for(let i = 0; i < currentElements.length;i++){
            if(newElement.search(currentElements[i].dataset.title) != -1){
                returnVal = true;
            }
        }

        return returnVal;
    }
    
    //removes old portfolio elements
    removeOldElements(newElements){
        let portFolioWrapper = CONSTANTS.PORTFOLIO_WRAPPER;
        let currentElements = jQuery(portFolioWrapper);
        let indexToKeep = [];


        //Find the elements to keep on page
        for(let i = 0; i < newElements.length;i++){
            for(let j = 0; j < currentElements.length;j++){
                if((newElements[i].search(currentElements[j].dataset.title) != -1)){
                    indexToKeep.push(j);
                }
            }
        }

        //remove the event handlers
        for(let i = 0; i < currentElements.length;i++){
            if(indexToKeep.indexOf(i) == -1){
                jQuery(portFolioWrapper + ':eq('+i+')').off('click',this,this.modalHandler);   
                jQuery(portFolioWrapper + ':eq('+i+')').off('click',this.mobileEvents); 
            }
        }
        //remove the dom elements
        for(let i = 0; i < currentElements.length;i++){
            if(indexToKeep.indexOf(i) == -1){
                jQuery(currentElements[i]).fadeOut(()=>currentElements[i].remove());
            }
        }


    }

    //adds new portfolio elements
    addNewElements(newElements){
        let portFolioWrapper = CONSTANTS.PORTFOLIO_WRAPPER;
        let wrapper = jQuery('#portfolio');
        let context = this;
        
        //add in the elements not already preset
        for(let i = 0; i < newElements.length && i < 6;i++){
            if(!this.checkIfOnPorfolio(newElements[i])){
                let elem = jQuery.parseHTML(newElements[i])[0];
                jQuery(elem).hide();
                jQuery(elem).css('display','block');
                
                window.setTimeout(()=>{
                    wrapper.append(elem);
                    let lastElem = jQuery('.portfolioItem').length - 1;
                    jQuery(portFolioWrapper + ':eq('+lastElem+')').on('click',context,this.modalHandler).fadeIn();      
                    jQuery(portFolioWrapper + ':eq('+lastElem+')').on('click',this.mobileEvents).fadeIn()
                    },CONSTANTS.NEW_ELEMENT_TIME_OUT);
            }
        }
    }

    //picks 6 random elements from the portfolio
    sixRandomElements(newElements){
        let indexesToKeep = [];
        let returnObj = [];

        if(newElements.length >= CONSTANTS.MAX_PORTFOLIOS){
            while(indexesToKeep.length < CONSTANTS.MAX_PORTFOLIOS){
                let randNum = Math.floor(Math.random() * newElements.length);

                //if index not already assigned, assign it
                if(indexesToKeep.every((val)=>{
                    return !(val == randNum);
                })){
                    indexesToKeep.push(randNum);
                }
            }

            for(let i = 0; i < indexesToKeep.length;i++){
                returnObj.push(newElements[indexesToKeep[i]]);
            }

        }else{
            returnObj = newElements;
        }

        return returnObj;
    }
    
    //Creates a modal for browsers at and greater the mobile width variable
    modalHandler(e){
        if(window.innerWidth < CONSTANTS.MOBILEWIDTH){
                this.setAttribute('data-toggle','disable');
        }else{
            if(jQuery('#thePostTitle').html() != this.dataset.title){
                e.data.resetIframe();

                jQuery('#thePost').attr('src',this.dataset.pagelink);
                jQuery('#thePostTitle').html(this.dataset.title);
                jQuery('#thePost').load(function () {
                    jQuery('#thePost').show();
                    jQuery('#loadingMessage').hide();
                })
                this.setAttribute('data-toggle','modal');
            }
        }
    }

    //Opens a new link if the width of the page is below the mobile boundry
    mobileEvents(e){
        if(window.innerWidth < CONSTANTS.MOBILEWIDTH){
            window.open(jQuery(this).data('pagelink'));
        }
    }
    
    //functions
    resetIframe(){
        jQuery('#thePost').hide();
        jQuery('#loadingMessage').show();
    }
    
}