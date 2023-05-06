this.BX = this.BX || {};
this.BX.Up = this.BX.Up || {};
(function (exports,main_core) {
	'use strict';

	var OverviewPopup = /*#__PURE__*/function () {
	  function OverviewPopup() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, OverviewPopup);
	    babelHelpers.defineProperty(this, "filters", []);
	    if (!main_core.Type.isStringFilled(options.nodeID)) {
	      throw new Error('Feedbacks: options.nodeID is required');
	    }
	    this.node = document.getElementById(options.nodeID);
	    if (!this.node) {
	      throw new Error("Feedbacks: element with ID \"".concat(options.nodeID, "\" not found"));
	    }
	    if (main_core.Type.isBoolean(options.subjects) && options.subjects) {
	      this.filters.push('subjects');
	    }
	    if (main_core.Type.isBoolean(options.edFormats) && options.edFormats) {
	      this.filters.push('education formats');
	    }
	    if (main_core.Type.isBoolean(options.city) && options.city) {
	      this.filters.push('cities');
	    }
	    console.log(options.price, main_core.Type.isBoolean(options.price));
	    if (main_core.Type.isBoolean(options.price) && options.price) {
	      this.filters.push('price');
	    }
	    if (main_core.Type.isBoolean(options.preferences) && options.preferences) {
	      this.filters.push('preferences');
	    }
	  }
	  babelHelpers.createClass(OverviewPopup, [{
	    key: "displayMessage",
	    value: function displayMessage() {
	      if (this.filters.length === 0) {
	        return;
	      }
	      var messageText = this.filters[0];
	      if (this.filters.length > 1) {
	        messageText = this.filters.join(', ');
	      }
	      var message = document.createElement('article');
	      message.classList.add('message', 'is-success');
	      message.innerHTML = "<div class=\"message-header\">\n\t\t\t\t\t\t\t\t<p>Success</p>\n\t\t\t\t\t\t\t\t<button class=\"delete\" id=\"popup-delete-button\"></button>\n\t\t\t\t\t\t\t  </div>\n\t\t\t\t\t\t\t  <div id=\"filter-used\" class=\"message-body\">\n\t\t\t\t\t\t\t\t  Filters used: ".concat(messageText, ".\n\t\t\t\t\t\t\t  </div>");
	      message.style.position = 'fixed';
	      message.style.margin = '40% 5% 5% 75%';
	      message.style.zIndex = '100';
	      this.node.appendChild(message);
	      var button = document.getElementById('popup-delete-button');
	      button.onclick = function () {
	        message.remove();
	      };
	    }
	  }]);
	  return OverviewPopup;
	}();

	exports.OverviewPopup = OverviewPopup;

}((this.BX.Up.Tutortoday = this.BX.Up.Tutortoday || {}),BX));
