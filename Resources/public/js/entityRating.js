var EntityRating = function (options) {

    var self = this;
    var sendForm = function () {
        $.post({
            url  : Routing.generate('cymo_entity_rating_rate', {
                id   : self.options.form.find('input.entity-id').val(),
                type : self.options.form.find('input.entity-type').val()
            }),
            data : self.options.form.serialize()
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