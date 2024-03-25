/**
 * Front-end JavaScript
 *
 * The JavaScript code you place here will be processed by esbuild. The output
 * file will be created at `../theme/js/script.min.js` and enqueued in
 * `../theme/functions.php`.
 *
 * For esbuild documentation, please see:
 * https://esbuild.github.io/
 */
import 'flowbite';
import { Tooltip } from 'flowbite';
import { gsap } from "gsap";
import { ScrollTrigger } from 'gsap/ScrollTrigger';

var wpnonce = ajax_scripts.ajax_nonce;
var post = ajax_scripts.post;



let mm = gsap.matchMedia();

gsap.registerPlugin(ScrollTrigger);

let postHeader = document.querySelector('#top.wp-block-cover .wp-block-cover__image-background');


window.addEventListener('scroll', (event) => {
    getScrollPosition();
    if(postHeader) {
        postHeader.style.transform = 'translateY(' + (scrollObject.y / 5) + 'px)';
    }
});


function getScrollPosition(){

    let masthead = document.querySelector('#masthead');
    scrollObject = {
       x: window.pageXOffset,
       y: window.pageYOffset
    }

    if(scrollObject.y > 50) {
        masthead.classList.add('minimized');
        masthead.classList.add('bg-white');
    } else {
        masthead.classList.remove('minimized');
        masthead.classList.remove('bg-white');
    }
    
}
getScrollPosition();


 gsap.from("#mtns1", {
    top: 50,
    ease: "power1.out",
    scrollTrigger: {
        trigger: "#colophon",
        start: "top bottom",
        end: "top 25%",
        scrub: true
    }, 
});

gsap.from("#mtns2", {
    top: 100,
    ease: "power1.out",
    scrollTrigger: {
        trigger: "#colophon",
        start: "top bottom",
        end: "top 25%",
        scrub: true
    }, 
});


gsap.from("#footer-logo", {
    top: 150,
    ease: "power1.out",
    scrollTrigger: {
        trigger: "#colophon",
        start: "top bottom",
        end: "top 25%",
        scrub: true
    }, 
});

gsap.from("#footer-content", {
    y: 350,
    ease: "power1.out",
    scrollTrigger: {
        trigger: "#colophon",
        start: "top bottom",
        end: "top 25%",
        scrub: true
    }, 
});

if(document.getElementById('get-involved')) {
    gsap.from("#get-involved .wp-block-column", {
        y: 100,
        opacity: 0,
        stagger: 0.2,
        ease: "power1.out",
        scrollTrigger: {
            trigger: "#get-involved",
            start: "top 80%",
            end: "top 50%",
            scrub: false
        }, 
    });
}

if(document.getElementById('callouts')) {
    gsap.from("#callouts .wp-block-column", {
        y: 100,
        opacity: 0,
        stagger: 0.2,
        ease: "power1.out",
        scrollTrigger: {
            trigger: "#callouts",
            start: "top 80%",
            end: "top 50%",
            scrub: false
        }, 
    });
}

/*
 * member counter 
*/
gsap.registerPlugin(ScrollTrigger);

const numbers = document.querySelectorAll('.counters');

var tl = gsap.timeline({
  scrollTrigger: {
    trigger: numbers,
    start: 'top center',
    markers: false,
    toggleActions: 'play none none none',
  },
});

gsap.utils.toArray('.counterOne').forEach(function (el) {
  var target = { val: 0 };
  tl.to(target, {
    val: el.getAttribute('data-number'),
    duration: 3,
    onUpdate: function () {
      el.innerText = target.val.toFixed();
    },
  });
});

// gsap.utils.toArray('.counterTwo').forEach(function (el) {
//   var target = { val: 0 };
//   tl.to(target, {
//     val: el.getAttribute('data-number'),
//     duration: 5,
//     onUpdate: function () {
//       el.innerText = target.val.toFixed(0);
//     },
//   });
// });

// gsap.utils.toArray('.counterThree').forEach(function (el) {
//   var target = { val: 0 };
//   tl.to(target, {
//     val: el.getAttribute('data-number'),
//     duration: 3,
//     onUpdate: function () {
//       el.innerText = target.val.toFixed(0);
//     },
//   });
// });

tl.play();

gsap.utils.toArray(document.querySelectorAll('.home #main > .page > .entry-content > *:not(#top), .blog .type-post')).forEach(function (el) {
    
    gsap.from(el, {
        y: 300,
        opacity: 0,
        stagger: 0.2,
        ease: "power1.out",
        scrollTrigger: {
            trigger: el,
            start: "top bottom",
            end: "top 50%",
            scrub: false
        }, 
    });
});

let saveFieldBtns = document.querySelectorAll('.save-field-btn');
let editableFields = document.querySelectorAll('form.editable-field-wrapper');

if(editableFields.length) {
    editableFields.forEach(function (el) {
        
        if(el.classList.contains('interests')) {
           
            let interestCbs = el.querySelectorAll('input[type=checkbox]');
            console.log(interestCbs);
            interestCbs.forEach( function(item, index) {
                item.addEventListener('change', function(e){
                    
                    let thisField = el.dataset.fieldName;
                    let gfEntryId = el.dataset.gfEntryId;
                    let gfFieldId = el.dataset.gfFieldId;
                    let checkboxChecked = false;
                    if(e.target.checked) {
                        checkboxChecked = true;
                    }
                    console.log(thisField, gfEntryId, gfFieldId);
                    let url = '/wp-admin/admin-ajax.php?action=goldrush_edit_member_field&_gfEntryId='+gfEntryId+'&_gfFieldId='+gfFieldId+'&_fieldName=' + thisField + '&_fieldVal='+e.target.value+'&_isChecked='+checkboxChecked+'&_wpnonce='+wpnonce;
                    const response = fetch(url, {
                        method: 'POST'
                    }).then(response => response.text())
                        .then(data => {
                            console.log(data);
                            // e.target.querySelector('.save-field-btn').classList.add('hidden');
                            // checkMark.classList.remove('opacity-0');
                            // checkMark.classList.remove('translate-y-5');
                            // spinner.classList.add('hidden');
                            // setTimeout(() => {
                            //     checkMark.classList.add('opacity-0');
                            //     checkMark.classList.add('translate-y-5');
                            // }, "2000");
                        })
                        .catch(err => console.log(err));
                    
                });
            });
            
            
        } else {

            // not the checkbox fields
            let infoLabel = el.closest('.editable-field-wrapper').querySelector('.info-label');
            let checkMark = el.closest('.editable-field-wrapper').querySelector('.check-mark');
            el.querySelector('input').addEventListener('focus', function(e){
                checkMark.classList.add('opacity-0');
                checkMark.classList.add('translate-y-5');
                infoLabel.classList.add('hidden');
                e.target.closest('.editable-field-wrapper').querySelector('.save-field-btn').classList.remove('hidden');
            });
            el.querySelector('input').addEventListener('blur', function(e){
                
                setTimeout(() => {
                    checkMark.classList.add('opacity-0');
                    checkMark.classList.add('translate-y-5');
                    
                }, "1500");

                setTimeout(() => {
                    infoLabel.classList.remove('hidden');
                    el.querySelector('.save-field-btn').classList.add('hidden');
                }, "2000");
                
            });

            el.addEventListener('submit', function(e){
                e.preventDefault();
    
                let thisField = e.target.dataset.fieldName;
                let gfEntryId = e.target.dataset.gfEntryId;
                let gfFieldId = e.target.dataset.gfFieldId;
                let spinner = e.target.closest('.editable-field-wrapper').querySelector('.field-spinner');
                
                let fieldVal = e.target.closest('.editable-field-wrapper').querySelector('.editable-field').value;
                let checkMark = e.target.closest('.editable-field-wrapper').querySelector('.check-mark');
                
                spinner.classList.remove('hidden');
                checkMark.classList.add('opacity-0');
                checkMark.classList.add('translate-y-5');
                let url = '/wp-admin/admin-ajax.php?action=goldrush_edit_member_field&_gfEntryId='+gfEntryId+'&_gfFieldId='+gfFieldId+'&_fieldName=' + thisField + '&_fieldVal='+fieldVal+'&_wpnonce='+wpnonce;
                
                const response = fetch(url, {
                    method: 'POST'
                }).then(response => response.text())
                    .then(data => {
                        
                        e.target.querySelector('.save-field-btn').classList.add('hidden');
                        checkMark.classList.remove('opacity-0');
                        checkMark.classList.remove('translate-y-5');
                        spinner.classList.add('hidden');
                        setTimeout(() => {
                            checkMark.classList.add('opacity-0');
                            checkMark.classList.add('translate-y-5');
                        }, "2000");
                    })
                    .catch(err => console.log(err));
                });

        }

        

        
    });
}


       

// editFieldBtn.addEventListener("click", async () => {
//     try {
//       const response = await fetch(url, { signal });
//       console.log("Download complete", response);
//     } catch (error) {
//       console.error(`Download error: ${error.message}`);
//     }
//   });