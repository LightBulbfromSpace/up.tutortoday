this.BX = this.BX || {};
this.BX.Up = this.BX.Up || {};
(function (exports,main_core) {
	'use strict';

	function _classPrivateMethodInitSpec(obj, privateSet) { _checkPrivateRedeclaration(obj, privateSet); privateSet.add(obj); }
	function _classPrivateFieldInitSpec(obj, privateMap, value) { _checkPrivateRedeclaration(obj, privateMap); privateMap.set(obj, value); }
	function _checkPrivateRedeclaration(obj, privateCollection) { if (privateCollection.has(obj)) { throw new TypeError("Cannot initialize the same private elements twice on an object"); } }
	function _classPrivateMethodGet(receiver, privateSet, fn) { if (!privateSet.has(receiver)) { throw new TypeError("attempted to get private field on non-instance"); } return fn; }
	var _feedbacksPerLoad = /*#__PURE__*/new WeakMap();
	var _page = /*#__PURE__*/new WeakMap();
	var _stars = /*#__PURE__*/new WeakMap();
	var _sendForm = /*#__PURE__*/new WeakSet();
	var _sanitize = /*#__PURE__*/new WeakSet();
	var Feedbacks = /*#__PURE__*/function () {
	  function Feedbacks() {
	    var _this = this;
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, Feedbacks);
	    _classPrivateMethodInitSpec(this, _sanitize);
	    _classPrivateMethodInitSpec(this, _sendForm);
	    _classPrivateFieldInitSpec(this, _feedbacksPerLoad, {
	      writable: true,
	      value: 3
	    });
	    _classPrivateFieldInitSpec(this, _page, {
	      writable: true,
	      value: 0
	    });
	    _classPrivateFieldInitSpec(this, _stars, {
	      writable: true,
	      value: void 0
	    });
	    if (!main_core.Type.isStringFilled(options.rootNodeID)) {
	      throw new Error('Feedbacks: options.rootNodeID is required');
	    }
	    this.rootNode = document.getElementById(options.rootNodeID);
	    if (!this.rootNode) {
	      throw new Error("Feedbacks: element with ID \"".concat(options.rootNodeID, "\" not found"));
	    }
	    if (!main_core.Type.isStringFilled(options.formID)) {
	      throw new Error('Feedbacks: options.formID is required');
	    }
	    this.form = document.getElementById(options.formID);
	    this.isActive = !!this.form;
	    if (!this.isActive) {
	      return;
	    }
	    if (!main_core.Type.isInteger(options.feedbackReceiverID)) {
	      throw new Error("Feedbacks: options.feedbackReceiver must be an integer");
	    }
	    this.feedbackReceiverID = options.feedbackReceiverID;
	    if (!main_core.Type.isStringFilled(options.toggleButtonID)) {
	      throw new Error('Feedbacks: options.toggleButton is required');
	    }
	    this.toggleButton = document.getElementById(options.toggleButtonID);
	    if (!this.toggleButton) {
	      throw new Error("Feedbacks: element with ID \"".concat(options.toggleButtonID, "\" not found"));
	    }
	    this.toggleButton.onclick = function () {
	      _this.openForm();
	    };
	    if (!main_core.Type.isStringFilled(options.feedbacksRootID)) {
	      throw new Error('Feedbacks: options.toggleButton is required');
	    }
	    this.feedbacksRootID = document.getElementById(options.feedbacksRootID);
	    if (!this.feedbacksRootID) {
	      throw new Error("Feedbacks: element with ID \"".concat(options.feedbacksRootID, "\" not found"));
	    }
	  }
	  babelHelpers.createClass(Feedbacks, [{
	    key: "openForm",
	    value: function openForm() {
	      var _this2 = this;
	      if (!this.isActive) {
	        return;
	      }
	      this.form.style.margin = '0.5rem 0 1rem 0';
	      this.form.innerHTML = "<div class=\"container-feedback-custom\">\n\t\t\t\t\t\t\t\t\t\t  <input type=\"text\" class=\"input-custom\" id=\"feedback-title\" placeholder=\"Title\" maxlength=\"100\">\n\t\t\t\t\t\t\t\t\t\t  <textarea class=\"textarea-custom\" id=\"feedback-description\" placeholder=\"Description\"></textarea>\n\t\t\t\t\t\t\t\t\t\t  <div class=\"stars-button-container\">\n\t\t\t\t\t\t\t\t\t\t\t  <div class=\"stars-container\">\n\t\t\t\t\t\t\t\t\t\t\t\t  <button id=\"s5\" class=\"fa fa-star\"></button>\n\t\t\t\t\t\t\t\t\t\t\t\t  <button id=\"s4\" class=\"fa fa-star\"></button>\n\t\t\t\t\t\t\t\t\t\t\t\t  <button id=\"s3\" class=\"fa fa-star\"></button>\n\t\t\t\t\t\t\t\t\t\t\t\t  <button id=\"s2\" class=\"fa fa-star\"></button>\n\t\t\t\t\t\t\t\t\t\t\t\t  <button id=\"s1\" class=\"fa fa-star\"></button>\n\t\t\t\t\t\t\t\t\t\t\t  </div>\n\t\t\t\t\t\t\t\t\t\t\t  <button class=\"button-plus-minus button-small-custom\" id=\"send-button\">Send</button>\n\t\t\t\t\t\t\t\t\t\t  </div>\n\t\t\t\t\t\t\t\t\t  </div>";
	      this.toggleButton.innerText = 'Close';
	      this.toggleButton.onclick = function () {
	        _this2.closeForm();
	      };
	      var _loop = function _loop(i) {
	        document.getElementById('s' + i).onclick = function () {
	          babelHelpers.classPrivateFieldSet(_this2, _stars, i);
	          for (var j = 1; j < 6; j++) {
	            document.getElementById('s' + j).classList.remove('star-selected');
	          }
	          for (var _j = 1; _j <= babelHelpers.classPrivateFieldGet(_this2, _stars); _j++) {
	            document.getElementById('s' + _j).classList.add('star-selected');
	          }
	        };
	      };
	      for (var i = 1; i < 6; i++) {
	        _loop(i);
	      }
	      this.sendButton = document.getElementById('send-button');
	      this.sendButton.onclick = function () {
	        _classPrivateMethodGet(_this2, _sendForm, _sendForm2).call(_this2);
	        _this2.closeForm();
	      };
	    }
	  }, {
	    key: "closeForm",
	    value: function closeForm() {
	      var _this3 = this;
	      if (!this.isActive) {
	        return;
	      }
	      while (this.form.lastChild) {
	        this.form.lastChild.remove();
	      }
	      this.toggleButton.innerText = 'Add feedback';
	      this.toggleButton.onclick = function () {
	        _this3.openForm();
	      };
	    }
	  }, {
	    key: "loadFeedbacksPerPage",
	    value: function loadFeedbacksPerPage() {
	      var _this4 = this;
	      BX.ajax({
	        url: '/profile/feedbacks/',
	        data: {
	          tutorID: this.feedbackReceiverID,
	          page: babelHelpers.classPrivateFieldGet(this, _page),
	          tutorsPerPage: babelHelpers.classPrivateFieldGet(this, _feedbacksPerLoad),
	          sessid: BX.bitrix_sessid()
	        },
	        method: 'POST',
	        dataType: 'json',
	        timeout: 10,
	        onsuccess: function onsuccess(res) {
	          //console.log(res)
	          if (res === null) {
	            return;
	          }
	          while (_this4.feedbacksRootID.lastChild) {
	            _this4.feedbacksRootID.lastChild.remove();
	          }
	          _this4.displayFeedbacksPerPage(res);
	        },
	        onfailure: function reject(e) {
	          console.log(e);
	        }
	      });
	    }
	  }, {
	    key: "displayFeedbacksPerPage",
	    value: function displayFeedbacksPerPage(feedbacks) {
	      var _this5 = this;
	      while (this.feedbacksRootID.lastChild) {
	        this.feedbacksRootID.lastChild.remove();
	      }
	      var noFbMsg = document.getElementById('no-feedbacks-message');
	      this.feedbacksRootID.style.justifyContent = 'space-between';
	      if (feedbacks['total'] === 0) {
	        if (_noFbMsg) {
	          return;
	        }
	        var _noFbMsg = document.createElement('div');
	        _noFbMsg.id = 'no-feedbacks-message';
	        _noFbMsg.classList.add('box');
	        _noFbMsg.innerText = 'No feedbacks yet';
	        this.feedbacksRootID.appendChild(_noFbMsg);
	        this.feedbacksRootID.style.justifyContent = 'center';
	        return;
	      }
	      if (noFbMsg) {
	        noFbMsg.remove();
	      }
	      this.feedbacksRootID.innerHTML = "<button class=\"feedback-button\" id=\"feedbacks-button-previous\">&lt;</button>\n\t\t\t\t\t\t\t\t\t\t  <div class=\"feedback-cards-container\" id=\"feedback-cards-container\"></div>\n\t\t\t\t\t\t\t\t\t\t  <button class=\"feedback-button\" id=\"feedbacks-button-next\">&gt;</button>";
	      var previousButton = document.getElementById('feedbacks-button-previous');
	      var feedbacksContainer = document.getElementById('feedback-cards-container');
	      var nextButton = document.getElementById('feedbacks-button-next');
	      previousButton.onclick = function () {
	        var _this$page, _this$page2;
	        previousButton.style.backgroundColor = babelHelpers.classPrivateFieldGet(_this5, _page) > 0 ? '#FFFFFF' : '#D9D9D9';
	        if (babelHelpers.classPrivateFieldGet(_this5, _page) <= 0) {
	          return;
	        }
	        babelHelpers.classPrivateFieldSet(_this5, _page, (_this$page = babelHelpers.classPrivateFieldGet(_this5, _page), _this$page2 = _this$page--, _this$page)), _this$page2;
	        _this5.loadFeedbacksPerPage();
	        console.log('previous', babelHelpers.classPrivateFieldGet(_this5, _page));
	      };
	      nextButton.onclick = function () {
	        var _this$page3, _this$page4;
	        var maxPage = Math.ceil(feedbacks['total'] / babelHelpers.classPrivateFieldGet(_this5, _feedbacksPerLoad) - 1);
	        nextButton.style.backgroundColor = babelHelpers.classPrivateFieldGet(_this5, _page) < maxPage ? '#FFFFFF' : '#D9D9D9';
	        if (babelHelpers.classPrivateFieldGet(_this5, _page) >= maxPage) {
	          return;
	        }
	        babelHelpers.classPrivateFieldSet(_this5, _page, (_this$page3 = babelHelpers.classPrivateFieldGet(_this5, _page), _this$page4 = _this$page3++, _this$page3)), _this$page4;
	        _this5.loadFeedbacksPerPage();
	        console.log('next', babelHelpers.classPrivateFieldGet(_this5, _page), feedbacks['total'] / babelHelpers.classPrivateFieldGet(_this5, _feedbacksPerLoad));
	      };
	      for (var i = 0; i < feedbacks['feedbacks'].length; i++) {
	        var elem = document.createElement('div');
	        elem.classList.add('feedback-card-container');
	        elem.innerHTML = "<a class=\"feedback-card-user-info-container\" href=\"/profile/".concat(feedbacks['feedbacks'][i]['student']['ID'], "/\">\n\t\t\t\t\t\t\t\t\t<img src=\"").concat(_classPrivateMethodGet(this, _sanitize, _sanitize2).call(this, feedbacks['feedbacks'][i]['student']['photo']), "\" class=\"photo-small img-rounded\" alt=\"avatar\">\n\t\t\t\t\t\t\t\t\t<div class=\"help\">").concat(_classPrivateMethodGet(this, _sanitize, _sanitize2).call(this, feedbacks['feedbacks'][i]['student']['surname']), "</div>\n\t\t\t\t\t\t\t\t\t<div class=\"help\">").concat(_classPrivateMethodGet(this, _sanitize, _sanitize2).call(this, feedbacks['feedbacks'][i]['student']['name']), "</div>\n\t\t\t\t\t\t\t\t</a>\n\t\t\t\t\t\t\t\t<div class=\"box feedback-card-custom\">\n\t\t\t\t\t\t\t\t\t<div class=\"title-feedback-custom\">\n\t\t\t\t\t\t\t\t\t\t<div class=\"title-custom\">").concat(_classPrivateMethodGet(this, _sanitize, _sanitize2).call(this, feedbacks['feedbacks'][i]['title']), "</div>\n\t\t\t\t\t\t\t\t\t\t<div class=\"stars-container\">\n\t\t\t\t\t\t\t\t\t\t\t<div id=\"s5-").concat(i, "-disabled\" class=\"fa fa-star\"></div>\n\t\t\t\t\t\t\t\t\t\t\t<div id=\"s4-").concat(i, "-disabled\" class=\"fa fa-star\"></div>\n\t\t\t\t\t\t\t\t\t\t\t<div id=\"s3-").concat(i, "-disabled\" class=\"fa fa-star\"></div>\n\t\t\t\t\t\t\t\t\t\t\t<div id=\"s2-").concat(i, "-disabled\" class=\"fa fa-star\"></div>\n\t\t\t\t\t\t\t\t\t\t\t<div id=\"s1-").concat(i, "-disabled\" class=\"fa fa-star\"></div>\n\t\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t\t<div class=\"br\"></div>\n\t\t\t\t\t\t\t\t\t<div class=\"feedback-body-custom\">\n\t\t\t\t\t\t\t\t\t\t").concat(_classPrivateMethodGet(this, _sanitize, _sanitize2).call(this, feedbacks['feedbacks'][i]['description']) === '' ? 'No description' : _classPrivateMethodGet(this, _sanitize, _sanitize2).call(this, feedbacks['feedbacks'][i]['description']), "\n\t\t\t\t\t\t\t\t\t</div>\n\t\t\t\t\t\t\t\t</div>");
	        feedbacksContainer.appendChild(elem);
	        for (var j = 1; j <= feedbacks['feedbacks'][i]['stars']; j++) {
	          document.getElementById("s".concat(j, "-").concat(i, "-disabled")).classList.add('star-selected');
	        }
	      }
	    }
	  }]);
	  return Feedbacks;
	}();
	function _sendForm2() {
	  var _this6 = this;
	  var title = document.getElementById('feedback-title');
	  var description = document.getElementById('feedback-description');
	  BX.ajax({
	    url: '/profile/feedbacks/add/',
	    data: {
	      receiverID: this.feedbackReceiverID,
	      title: title.value,
	      description: description.value,
	      stars: babelHelpers.classPrivateFieldGet(this, _stars),
	      sessid: BX.bitrix_sessid()
	    },
	    method: 'POST',
	    dataType: 'json',
	    timeout: 10,
	    onsuccess: function onsuccess(res) {
	      console.log(res);
	      _this6.loadFeedbacksPerPage();
	    },
	    onfailure: function reject(e) {
	      console.log(e);
	    }
	  });
	}
	function _sanitize2(string) {
	  var map = {
	    '&': '&amp;',
	    '<': '&lt;',
	    '>': '&gt;',
	    '"': '&quot;',
	    "'": '&#x27;',
	    "/": '&#x2F;'
	  };
	  var reg = /[&<>"'/]/ig;
	  return string.replace(reg, function (match) {
	    return map[match];
	  });
	}

	exports.Feedbacks = Feedbacks;

}((this.BX.Up.Tutortoday = this.BX.Up.Tutortoday || {}),BX));
