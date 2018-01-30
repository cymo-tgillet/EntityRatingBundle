/**
 * Created by cymo on 25/01/18.
 */

class StarRating {
    constructor(options) {
        this.options = options;
        this.options.starItem.on('click', this.starClicked);
        console.log(options);
    }

    starClicked(e) {
        console.log(e.target);
        $.post({
            url : Routing.generate('cymo_entity_rating_rate', {type : 'itinerary', id : 1, rate : e.target.value})
        }).then((response) => {
            console.log(response);
        });
    }
}