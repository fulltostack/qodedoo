$(document).ready(function(e) { 
  $("body").append('<div id="tooltip_wrapper" class="group"></div>');
  $("body").append('<div id="tooltip_wrapper" class="group"></div>');
  $(".custom-select").wrap('<div class="customselectwrapper"></div>');  
  $('input[type="file"]').wrap('<div class="customfilewrapper"></div>').parent().after('<div class="inputfilewrapper"><div class="uploadedname"><span>File Name</span><span class="small_icon"><img src="images/crox.png"></span></div></div>');
  $(".customfilewrapper").append('<button class="inputfilebtn"></button>');
  
  $(".toggle-password").click(function() {

 $(this).toggleClass("fa-eyes fa-eyes-slash");
 var input = $($(this).attr("toggle"));
 if (input.attr("type") == "password") {
   input.attr("type", "text");
 } else {
   input.attr("type", "password");
 }
});

});

//input type file
// $('input[type="file"]').change(function(){
// 	$(this).parent().next('.inputfilewrapper').children(".uploadedname").children("span:first-child").html( $(this)[0].files[0].name );
// 	console.log($(this)[0].files[0]);
// });

//






//for radio button
function customRadiobutton(radiobuttonName){
	var radioButton = $('input[name="'+ radiobuttonName +'"]');
	$(radioButton).each(function(){
		$(this).wrap( "<span class='custom-rating'></span>" );
		if($(this).is(':checked')){				
			$(this).parent().addClass("selected");
		}
	});
	$(radioButton).click(function(){
		radioname = $(this).attr("name");
		$("input.custom-rating[type=radio]").each(function(index, element) {
			if ( $(this).attr("name") == radioname ){
				$(this).parent("span").removeClass("selected");
			}
		});
			$(this).parent("span").addClass("selected")
	});
}

$(document).ready(function (){
	customRadiobutton("rating-input-1-5");
	customRadiobutton("rating-input-1-4");
	customRadiobutton("rating-input-1-3");
	customRadiobutton("rating-input-1-2");
	customRadiobutton("rating");
})

$(document).ready(function (t) {
    "use strict";

    function e(e, o) {
        return this.each(function() {
            var s = t(this),
                n = s.data("bs.modal"),
                a = t.extend({}, i.DEFAULTS, s.data(), "object" == typeof e && e);
            n || s.data("bs.modal", n = new i(this, a)), "string" == typeof e ? n[e](o) : a.show && n.show(o)
        })
    }
    var i = function(e, i) {
        this.options = i, this.$body = t(document.body), this.$element = t(e), this.$dialog = this.$element.find(".modal-dialog"), this.$backdrop = null, this.isShown = null, this.originalBodyPad = null, this.scrollbarWidth = 0, this.ignoreBackdropClick = !1, this.options.remote && this.$element.find(".modal-content").load(this.options.remote, t.proxy(function() {
            this.$element.trigger("loaded.bs.modal")
        }, this))
    };
    i.VERSION = "3.3.4", i.TRANSITION_DURATION = 300, i.BACKDROP_TRANSITION_DURATION = 150, i.DEFAULTS = {
        backdrop: !0,
        keyboard: !0,
        show: !0
    }, i.prototype.toggle = function(t) {
        return this.isShown ? this.hide() : this.show(t)
    }, i.prototype.show = function(e) {
        var o = this,
            s = t.Event("show.bs.modal", {
                relatedTarget: e
            });
        this.$element.trigger(s), this.isShown || s.isDefaultPrevented() || (this.isShown = !0, this.checkScrollbar(), this.setScrollbar(), this.$body.addClass("modal-open"), this.escape(), this.resize(), this.$element.on("click.dismiss.bs.modal", '[data-dismiss="modal"]', t.proxy(this.hide, this)), this.$dialog.on("mousedown.dismiss.bs.modal", function() {
            o.$element.one("mouseup.dismiss.bs.modal", function(e) {
                t(e.target).is(o.$element) && (o.ignoreBackdropClick = !0)
            })
        }), this.backdrop(function() {
            var s = t.support.transition && o.$element.hasClass("fade");
            o.$element.parent().length || o.$element.appendTo(o.$body), o.$element.show().scrollTop(0), o.adjustDialog(), s && o.$element[0].offsetWidth, o.$element.addClass("in").attr("aria-hidden", !1), o.enforceFocus();
            var n = t.Event("shown.bs.modal", {
                relatedTarget: e
            });
            s ? o.$dialog.one("bsTransitionEnd", function() {
                o.$element.trigger("focus").trigger(n)
            }).emulateTransitionEnd(i.TRANSITION_DURATION) : o.$element.trigger("focus").trigger(n)
        }))
    }, i.prototype.hide = function(e) {
        e && e.preventDefault(), e = t.Event("hide.bs.modal"), this.$element.trigger(e), this.isShown && !e.isDefaultPrevented() && (this.isShown = !1, this.escape(), this.resize(), t(document).off("focusin.bs.modal"), this.$element.removeClass("in").attr("aria-hidden", !0).off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"), this.$dialog.off("mousedown.dismiss.bs.modal"), t.support.transition && this.$element.hasClass("fade") ? this.$element.one("bsTransitionEnd", t.proxy(this.hideModal, this)).emulateTransitionEnd(i.TRANSITION_DURATION) : this.hideModal())
    }, i.prototype.enforceFocus = function() {
        t(document).off("focusin.bs.modal").on("focusin.bs.modal", t.proxy(function(t) {
            this.$element[0] === t.target || this.$element.has(t.target).length || this.$element.trigger("focus")
        }, this))
    }, i.prototype.escape = function() {
        this.isShown && this.options.keyboard ? this.$element.on("keydown.dismiss.bs.modal", t.proxy(function(t) {
            27 == t.which && this.hide()
        }, this)) : this.isShown || this.$element.off("keydown.dismiss.bs.modal")
    }, i.prototype.resize = function() {
        this.isShown ? t(window).on("resize.bs.modal", t.proxy(this.handleUpdate, this)) : t(window).off("resize.bs.modal")
    }, i.prototype.hideModal = function() {
        var t = this;
        this.$element.hide(), this.backdrop(function() {
            t.$body.removeClass("modal-open"), t.resetAdjustments(), t.resetScrollbar(), t.$element.trigger("hidden.bs.modal")
        })
    }, i.prototype.removeBackdrop = function() {
        this.$backdrop && this.$backdrop.remove(), this.$backdrop = null
    }, i.prototype.backdrop = function(e) {
        var o = this,
            s = this.$element.hasClass("fade") ? "fade" : "";
        if (this.isShown && this.options.backdrop) {
            var n = t.support.transition && s;
            if (this.$backdrop = t('<div class="modal-backdrop ' + s + '" />').appendTo(this.$body), this.$element.on("click.dismiss.bs.modal", t.proxy(function(t) {
                    return this.ignoreBackdropClick ? void(this.ignoreBackdropClick = !1) : void(t.target === t.currentTarget && ("static" == this.options.backdrop ? this.$element[0].focus() : this.hide()))
                }, this)), n && this.$backdrop[0].offsetWidth, this.$backdrop.addClass("in"), !e) return;
            n ? this.$backdrop.one("bsTransitionEnd", e).emulateTransitionEnd(i.BACKDROP_TRANSITION_DURATION) : e()
        } else if (!this.isShown && this.$backdrop) {
            this.$backdrop.removeClass("in");
            var a = function() {
                o.removeBackdrop(), e && e()
            };
            t.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one("bsTransitionEnd", a).emulateTransitionEnd(i.BACKDROP_TRANSITION_DURATION) : a()
        } else e && e()
    }, i.prototype.handleUpdate = function() {
        this.adjustDialog()
    }, i.prototype.adjustDialog = function() {
        var t = this.$element[0].scrollHeight > document.documentElement.clientHeight;
        this.$element.css({
            paddingLeft: !this.bodyIsOverflowing && t ? this.scrollbarWidth : "",
            paddingRight: this.bodyIsOverflowing && !t ? this.scrollbarWidth : ""
        })
    }, i.prototype.resetAdjustments = function() {
        this.$element.css({
            paddingLeft: "",
            paddingRight: ""
        })
    }, i.prototype.checkScrollbar = function() {
        var t = window.innerWidth;
        if (!t) {
            var e = document.documentElement.getBoundingClientRect();
            t = e.right - Math.abs(e.left)
        }
        this.bodyIsOverflowing = document.body.clientWidth < t, this.scrollbarWidth = this.measureScrollbar()
    }, i.prototype.setScrollbar = function() {
        var t = parseInt(this.$body.css("padding-right") || 0, 10);
        this.originalBodyPad = document.body.style.paddingRight || "", this.bodyIsOverflowing && this.$body.css("padding-right", t + this.scrollbarWidth)
    }, i.prototype.resetScrollbar = function() {
        this.$body.css("padding-right", this.originalBodyPad)
    }, i.prototype.measureScrollbar = function() {
        var t = document.createElement("div");
        t.className = "modal-scrollbar-measure", this.$body.append(t);
        var e = t.offsetWidth - t.clientWidth;
        return this.$body[0].removeChild(t), e
    };
    var o = t.fn.modal;
    t.fn.modal = e, t.fn.modal.Constructor = i, t.fn.modal.noConflict = function() {
        return t.fn.modal = o, this
    }, t(document).on("click.bs.modal.data-api", '[data-toggle="modal"]', function(i) {
        var o = t(this),
            s = o.attr("href"),
            n = t(o.attr("data-target") || s && s.replace(/.*(?=#[^\s]+$)/, "")),
            a = n.data("bs.modal") ? "toggle" : t.extend({
                remote: !/#/.test(s) && s
            }, n.data(), o.data());
        o.is("a") && i.preventDefault(), n.one("show.bs.modal", function(t) {
            t.isDefaultPrevented() || n.one("hidden.bs.modal", function() {
                o.is(":visible") && o.trigger("focus")
            })
        }), e.call(n, a, this)
    })
});



/* Custom js for signup and login */
var xhr = 0;
var username_value = '';
var regex_1 = /^[A-Za-z0-9._]{0,30}$/
var regex_2 = /^(?=.{8,})(?=.*[a-z0-9A-Z])(?=.*[@#$%^&+=]).*$/
var regex_3 = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var regex_4 = /[0-9]{8,12}/
jQuery(document).ready(function($){
    $('#username').keyup(function(){
        username_value = $('#username').val();
        if(username_value == '')
        {
            $('#showUsernameErrorMsg').show();
            $('.error-cancel').hide();
            $('.error-verified').hide();
            $('#usernameNext').prop('disabled',true);
        }
        else
        {
            $('#showUsernameErrorMsg').hide();
        }

        var is_valid_value_1 = regex_1.test(username_value);

        if(!is_valid_value_1)
        {
            $('#showUsernameErrorMsg').show();
            $('.error-verified').hide();
            $('.error-cancel').show();
            $('#usernameNext').prop('disabled',true);
        }

        if(username_value == '')
        {
            $('#showUsernameErrorMsg').html('Please fill your input')
        }
        else
        {
            $('#showUsernameErrorMsg').html('Please fill valid input')
        }

        var data = {
            action: 'check_username',
            username : username_value,
        };
        if(xhr) xhr.abort();
        if(username_value!=='' && is_valid_value_1)
        {
            xhr = $.ajax({
            url : ajaxurl,
            type: 'get',
            async : true,
            data : data,
            success : function(response) {
                    if(response == 0)
                    {
                        $('.error-cancel').hide();
                        $('.error-verified').show();
                        $('#usernameNext').prop('disabled',false);
                    }
                    else
                    {
                        $('.error-verified').hide();   
                        $('.error-cancel').show();
                        $('#usernameNext').prop('disabled',true);
                    }
                    if(username_value == '' || !is_valid_value_1)
                    {
                        $('#usernameNext').prop('disabled',true);
                    }
                },
            });
        }
    })
});

var persona_value = '';
jQuery(document).ready(function($){
    $('#persona').keyup(function(){
        persona_value = $('#persona').val();

        if(persona_value == '')
        {
            $('#showPersonaErrorMsg').show();
            $('.error-cancel').show();
            $('.error-verified').hide();
            $('#personaNext').prop('disabled',true);
        }
        else
        {
            $('#showPersonaErrorMsg').hide();
            $('.error-verified').show();
            $('.error-cancel').hide();
            $('#personaNext').prop('disabled',false);
        }

        var is_valid_value_2 = regex_1.test(persona_value);

        if(!is_valid_value_2)
        {
            $('#showPersonaErrorMsg').show();
            $('.error-verified').hide();
            $('.error-cancel').show();
            $('#personaNext').prop('disabled',true);
        }

        if(persona_value == '')
        {
            $('#showPersonaErrorMsg').html('Please fill your input')
        }
        else
        {
            $('#showPersonaErrorMsg').html('Please fill valid input')
        }
    });
});

var password_value = '';
jQuery(document).ready(function($){
    $('#password').keyup(function(){
        password_value = $('#password').val();
        if(password_value == '')
        {
            $('#showPasswordErrorMsg').show();
            $('.error-cancel').show();
            $('.error-verified').hide();
            $('#passwordNext').prop('disabled',true);
        }
        else
        {
            $('#showPasswordErrorMsg').hide();
            $('.error-verified').show();
            $('.error-cancel').hide();
            $('#passwordNext').prop('disabled',false);
        }

        var is_valid_value_3 = regex_2.test(password_value);

        if(!is_valid_value_3)
        {
            $('#showPasswordErrorMsg').show();
            $('.error-verified').hide();
            $('.error-cancel').show();
            $('#passwordNext').prop('disabled',true);
        }

        if(persona_value == '')
        {
            $('#showPasswordErrorMsg').html('Please fill your input')
        }
        else
        {
            $('#showPasswordErrorMsg').html('Please fill valid input')
        }
    });
});

var date_value = '';
jQuery(document).ready(function($){
    $('#userDate').change(function(){
        date_value = $('#userDate').val();
        if(date_value == '')
        {
            $('#showDateErrorMsg').show();
            $('.error-cancel').show();
            $('.error-verified').hide();
            $('#dateNext').prop('disabled',true);
        }
        else
        {
            $('#showDateErrorMsg').hide();
            $('.error-verified').show();
            $('.error-cancel').hide();
            $('#dateNext').prop('disabled',false);
        }
    });
});

var email_value = '';
jQuery(document).ready(function($){
    $('#userEmail').keyup(function(){
        email_value = $('#userEmail').val();
        if(email_value == '')
        {
            $('#showEmailErrorMsg').show();
            $('.error-cancel').show();
            $('.error-verified').hide();
            $('#emailNext').prop('disabled',true);
        }
        else
        {
            $('#showEmailErrorMsg').hide();
            $('.error-verified').show();
            $('.error-cancel').hide();
            $('#emailNext').prop('disabled',false);
        }

        var is_valid_value_4 = regex_3.test(email_value);

        if(!is_valid_value_4)
        {
            $('#showEmailErrorMsg').show();
            $('.error-verified').hide();
            $('.error-cancel').show();
            $('#emailNext').prop('disabled',true);
        }

        if(email_value == '')
        {
            $('#showEmailErrorMsg').html('Please fill your input')
        }
        else
        {
            $('#showEmailErrorMsg').html('Please fill valid input')
        }
    });
});

var mobile_value = '';
jQuery(document).ready(function($){
    $('#userMobile').keyup(function(){
        mobile_value = $('#userMobile').val();
        
        var is_valid_value_5 = regex_4.test(mobile_value);

        if(!is_valid_value_5)
        {
            $('#showMobileErrorMsg').show();
            $('.error-verified').hide();
            $('.error-cancel').show();
            $('#mobileNext').prop('disabled',true);
        }
        else
        {
            $('#showMobileErrorMsg').hide();
            $('.error-verified').show();
            $('.error-cancel').hide();
            $('#mobileNext').prop('disabled',false);
        }

        if(mobile_value == '')
        {
            $('#showMobileErrorMsg').hide();
            $('.error-verified').show();
            $('.error-cancel').hide();
            $('#mobileNext').prop('disabled',false);
        }
    });
});

function showSignupStep(id)
{
    $('.error-cancel,.error-verified').hide();
    $('.signup-steps').hide();
    $('#'+id).show();
    $('#usernameValue').html(username_value);
    $('#emailValue').html(email_value);
    $('#personaValue').html(persona_value);
}

function forgot_password()
{
    var user_details = {
        action: 'forgot_password',
        email:$('#forgotEmail').val(),
    };

    xhr = $.ajax({
    url : ajaxurl,
    type: 'post',
    async : true,
    data : user_details,
    success : function(response) {
            if(response !== 0)
            {
                $('.forgot-popup').modal('show');
            }
        },
    });
}

function register_user()
{
    var formData = new FormData();
    formData.append('updoc', $('input[type=file]')[0].files[0]);
    formData.append('action', "register_user");
    formData.append('username', username_value);
    formData.append('password', password_value);
    formData.append('email', email_value);
    formData.append('mobile', mobile_value);
    formData.append('dob', date_value);
    formData.append('persona', persona_value);

    xhr = $.ajax({
        url: ajaxurl,
        type: "POST",
        data:formData,cache: false,
        processData: false, // Don’t process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success:function(response) {
            console.log(response);
        },

    });
}

function imageChange(file)
{
    var fileUrl = window.URL.createObjectURL(file)
    $('.inputfilebtn').css('background','url("'+fileUrl+'") no-repeat')
}