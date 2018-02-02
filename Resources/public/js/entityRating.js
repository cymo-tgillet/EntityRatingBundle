var EntityRating = function (options) {

    var self = this;
    var sendForm = function (e) {
        var data = self.options.form.serializeArray();
        data.push({name : 'form_name', value : self.options.form.attr('name')});

        var sameEntityRatingIdForms = $('form[data-entity-rating-id="' + self.options.form.data('entity-rating-id') + '"]');
        sameEntityRatingIdForms.find(self.options.radioButtonClass + '[value=' + e.target.value + ']').prop('checked', true);
        $.post({
            url  : Routing.generate('cymo_entity_rating_rate', {
                id   : self.options.form.find('input.entity-id').val(),
                type : self.options.form.find('input.entity-type').val()
            }),
            data : data
        }).then(function (response) {
            if (self.options.successCallback !== undefined) {
                self.options.successCallback(response);
            }
        }).fail(function (response) {
            if (self.options.errorCallback !== undefined) {
                self.options.errorCallback(response);
            }
        });
    };

    (function __construct() {
        self.options = options;
        self.options.form.find(self.options.radioButtonClass).change(sendForm);
    })();
};