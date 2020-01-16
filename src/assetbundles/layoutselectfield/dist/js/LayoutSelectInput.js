/** global: Craft */
/** global: Garnish */
if (!window.Kurious) {
	window.Kurious = {};
}
/**
 * Asset Select input
 */
Kurious.LayoutSelectInput = Craft.BaseElementSelectInput.extend({
	requestId: 0,
	hud: null,
	uploader: null,
	progressBar: null,

	originalFilename: '',
	originalExtension: '',

	init: function() {
		this.base.apply(this, arguments);

		this.addListener(this.$elementsContainer, 'keydown', this._onKeyDown.bind(this));
		this.elementSelect.on('focusItem', this._onElementFocus.bind(this));
	},

	createModal: function() {
		return new Kurious.LayoutSelectModal(this.settings.items, this.getModalSettings());
	},

	getModalSettings: function() {
		return $.extend(
			{
				closeOtherModals: false,
				storageKey: this.modalStorageKey,
				sources: this.settings.sources,
				criteria: this.settings.criteria,
				multiSelect: false,
				showSiteMenu: this.settings.showSiteMenu,
				disabledElementIds: this.getDisabledElementIds(),
				onSelect: $.proxy(this, 'onModalSelect')
			},
			this.settings.modalSettings
		);
	},

	onModalSelect: function($el) {
		this.selectElement($el);
		//this.updateDisabledElementsInModal();
	},

	selectElement: function($el) {
		//for (var i = 0; i < elements.length; i++) {
		var elementInfo = $el,
			$element = this.createNewElement($el);

		//this.removeElements(this.getElements());
		this.getElements().remove();
		this.appendElement($element);
		this.addElements($element);
		this.animateElementIntoPlace($el, $element);
		//}

		this.onSelectElements($el);
	},

	createNewElement: function($el) {
		var $element = $el.clone();

		// Make a couple tweaks
		Craft.setElementSize($element, this.settings.viewMode === 'large' ? 'large' : 'small');
		$element.addClass('layout').removeClass('active');
		$element.prepend('<input type="hidden" name="' + this.settings.name + '" value="' + $element.data('id') + '">');
		//$element.append('<div class="btn add icon dashed" tabindex="0" style="position: absolute; top: 0px; left: 0px;">Choose</div>');

		return $element;
	},

	removeElements: function($elements) {
		if (this.settings.selectable) {
			this.elementSelect.removeItems($elements);
		}

		/*if (this.modal) {
			var ids = [];

			for (var i = 0; i < $elements.length; i++) {
				var id = $elements.eq(i).data('id');

				if (id) {
					ids.push(id);
				}
			}

			if (ids.length) {
				this.modal.elementIndex.enableElementsById(ids);
			}
		}*/

		// Disable the hidden input in case the form is submitted before this element gets removed from the DOM
		$elements.children('input').prop('disabled', true);

		this.$elements = this.$elements.not($elements);
		this.updateAddElementsBtn();

		this.onRemoveElements();
	}
});
