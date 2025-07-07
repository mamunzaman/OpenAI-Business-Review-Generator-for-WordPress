function CopyToClipboard(id)
{
  var r = document.createRange();
  r.selectNode(document.getElementById(id));
  window.getSelection().removeAllRanges();
  window.getSelection().addRange(r);
  document.execCommand('copy');
  //window.getSelection().removeAllRanges();

  jQuery.magnificPopup.open({
    items: {
      src: '#mm-googlemap-alert-with-info',
    },
    closeOnContentClick: false,
    closeOnBgClick: false,
    showCloseBtn: true,
    modal: false,
    type: 'inline'
  });

}



document.addEventListener("DOMContentLoaded", function (e) {
  console.log("ready");

  let selfButtonClick = 0;

  jQuery('#normal-review-butoon-container').show();

  jQuery('.mm-review-open-review-google, .mm-review-ask-new-review').hide();

  function printStringByLetter(paragraph_id,myDiv_id) {
    // console.log(paragraph_id);
    // console.log(myDiv_id);
    var myDiv = document.getElementById(myDiv_id);

    myDiv.style.display = "block";

    var text = document.getElementById(paragraph_id).innerHTML;
    // myDiv.innerHTML = "";
    document.getElementById(myDiv_id).innerHTML = "";
    // console.log(text.length);
    var index = 0;
    var intervalId = setInterval(function() {
      myDiv.innerHTML += text.charAt(index);
      index++;
      // console.log(index);
      if(index == text.length) {
        clearInterval(intervalId);
        index = 0;
        text = "";
      }
    }, 5);
  }



  let myTextarea = jQuery('.mm-textarea-container');
  myTextarea.slideUp();
  jQuery('#mm-review-normal-button').hide();


  let mmAiGeneratedContainer = jQuery(".mm-ai-genaration-form-with-data");
  let reviewForm = document.getElementById("mm-google-review-ai-form");
  let url = reviewForm.dataset.url;










  mmAiGeneratedContainer.hide();

  if (document.querySelector('input[name="rate"]')) {
    document.querySelectorAll('input[name="rate"]').forEach((elem) => {
      elem.addEventListener("change", function (event) {
        event.preventDefault();
        var item = event.target.value;
        let params = new URLSearchParams(new FormData(reviewForm));





        if(item > 3){
          //console.log(item);
          //jQuery('#generated_review_google').show();
          jQuery('#normal-review-butoon-container').slideUp();
          if(jQuery('#generated_review_google').text().length > 0){
            jQuery('#generated_review_google').slideDown();
          }


          myTextarea.slideUp('normal');
          mmAiGeneratedContainer.slideDown('normal');

          //myTextarea.slideDown();
          jQuery('#mm-review-normal-button').show();

        }else{
          jQuery('#normal-review-butoon-container').slideDown();
          jQuery('#generated_review_google').slideUp();
          myTextarea.slideDown('normal');
          mmAiGeneratedContainer.slideUp('normal');

          //myTextarea.slideDown();
          jQuery('#mm-review-normal-button').show();
        }

      });
    });
  }



  // this is only send request to generate the review Texts.
  if (document.querySelector('input[name="mm-review-generate-button"]')) {
    let mmReviewGenerateButton = document.getElementById("mm-review-generate-button");
    mmReviewGenerateButton.addEventListener("click", function (event) {
        event.preventDefault();
        let params = new URLSearchParams(new FormData(reviewForm));
      //jQuery('#google_review_business_prompt_text_type').val('0');
        reviewForm.requestSubmit();
      });
  }

  if (document.querySelector('input[name="mm-review-ask-new-review"]')) {
    let mmReviewGenerateButton = document.getElementById("mm-review-ask-new-review");
    mmReviewGenerateButton.addEventListener("click", function (event) {
      event.preventDefault();
      jQuery('#generated_review_google').toggleClass();
      //jQuery('.mm-textarea-container').slideUp();
      //jQuery('#google_review_business_prompt_text_type').val('0');


      let params = new URLSearchParams(new FormData(reviewForm));
      reviewForm.requestSubmit();
    });
  }


  if (document.querySelector('input[name="mm-google-close-goto-link"]')) {
    let googleGotoLink = document.getElementById("mm-google-close-goto-link");
    googleGotoLink.addEventListener("click", function (event) {
      event.preventDefault();
      //jQuery('#generated_review_google').toggleClass();
      //jQuery('.mm-textarea-container').slideUp();
      //jQuery('#google_review_business_prompt_text_type').val('0');
      jQuery('#google_goto_popup_link_trigger').val('1');


      let params = new URLSearchParams(new FormData(reviewForm));
      reviewForm.requestSubmit();

      var magnificPopup = jQuery.magnificPopup.instance;
      // save instance in magnificPopup variable
      magnificPopup.close();
      // Close popup that is currently opened

    });
  }


  const copyBtn = document.getElementById('mm-review-copy-button')
  const copyText = document.getElementById('copyText')
  let ele = document.getElementById('generated_review_google');


  // selecting loading div
  const loader = document.querySelector("#loading");
  const copyButton = document.querySelector("#mm-review-copy-button");

  //const showAllButton = document.querySelectorAll(".mm-common-button-hide");

  //console.log(showAllButton.length);
// showing loading
  function displayLoading() {
    loader.classList.add("display");
    //copyButton.classList.remove('showButtonCopy');
    jQuery('#mm-review-copy-button').removeClass('showButtonCopy');
    // to stop loading after some time
    setTimeout(() => {
      loader.classList.remove("display");
    }, 90000); // update here if you make wait longer time preloader. Note this preloader automatically hide when
    // response reseived. You can update the time as long as you want in ms.
  }

// hiding loading
  function hideLoading() {
    loader.classList.remove("display");
    //copyButton.classList.add('showButtonCopy');
    jQuery('#mm-review-copy-button').addClass('showButtonCopy');

  }




  reviewForm.addEventListener("submit", (e) => {
    e.preventDefault();
    displayLoading();
    console.log(url);

    let params = new URLSearchParams(new FormData(reviewForm));

    // this is important to check what data submitted
    for (const value of params.values()) {
      console.log(value);
    }

    fetch(url, {
      method: "POST",
      body: params,
    })
      .then((res) => res.json())

      .catch((error) => {
        console.log("Trigger Error");
      })

      .then((response) => {
        //console.log("Run succefully");
        // deal with response
        hideLoading();
        jQuery('#mm-review-generate-button, #mm-review-copy-button').hide();
        jQuery('.mm-review-open-review-google, .mm-review-ask-new-review').show();

        console.log(response);
        //console.log(response.rate.choices[0].message.content);
        //console.log(response.status);

        let responseStatus = response;

        console.log(response);

        if(responseStatus.status === 'error'){

          jQuery.magnificPopup.open({
            items: {
              //src: '<div class="white-popup">Dynamically created popup</div>',
              src: '#mm-googlemap-error-text-alert',
              type: 'inline'
            },
            callbacks: {
              beforeOpen: function() {
                // Will fire when this exact popup is opened
                // this - is Magnific Popup object
                jQuery('#mm-review-open-review-google').hide();
                jQuery('#mm-review-ask-new-review').hide();
              }
            },

            closeOnContentClick: false,
            closeOnBgClick: false,
            showCloseBtn: true,
            modal: false,

          });



        } else if( responseStatus.status === 'error_token' ){

          jQuery.magnificPopup.open({
            items: {
              //src: '<div class="white-popup">Dynamically created popup</div>',
              src: '<div class="white-popup">'+ response.content +'</div>',
              type: 'inline'
            },
            callbacks: {
              beforeOpen: function() {
                //jQuery('#mm-review-open-review-google').prop('disabled', true);
                jQuery('#mm-review-ask-new-review').prop('disabled', true);
                //jQuery('#mm-review-ask-new-review').hide();
              }
            },

            closeOnContentClick: false,
            closeOnBgClick: false,
            showCloseBtn: true,
            modal: false,

          });

        }else if( responseStatus.popup_status === 1 ) {
          //alert('Copy Done');

          var googleGotoOpenLink = document.getElementById('mm-google-close-goto-link');
          var openLinkFromData = googleGotoOpenLink.getAttribute('data-gotid'); // fruitCount = '12'
          let currToekn = response.token;
          jQuery('#google_remaining_token').val(currToekn);

          //let newTab = window.open();
          window.location.href = openLinkFromData;

        }else{


            //let res = response.rate.choices[0].message.content;
            let res = response.data.choices[0].message.content;
            //let currToekn = response.token;
            console.log(res);
            //jQuery('#google_remaining_token').val(currToekn);
            //jQuery()
            ele.value = '';
            jQuery('#return_chat_text_text').html(res);
            jQuery('#google_mm_already_generated_text').val(res);

          printStringByLetter("return_chat_text_text","generated_review_google");

          document.querySelectorAll('.mm-common-button-hide').forEach((elem) => {
            elem.classList.add('showButtonCopy');
          });



        }




      });
  });
});


