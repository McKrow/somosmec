$("ul").on('click', 'li', function (e) {
    if (e.ctrlKey || e.metaKey) {
        $(this).toggleClass("selected");
    } else {
        $(this).addClass("selected").siblings().removeClass('selected');
    }
}).sortable({
    connectWith: "ul",
    delay: 150, //Needed to prevent accidental drag when trying to select
    revert: 0,
    helper: function (e, item) {
        //Basically, if you grab an unhighlighted item to drag, it will deselect (unhighlight) everything else
        if (!item.hasClass('selected')) {
            item.addClass('selected').siblings().removeClass('selected');
        }
        
        //////////////////////////////////////////////////////////////////////
        //HERE'S HOW TO PASS THE SELECTED ITEMS TO THE `stop()` FUNCTION:
        
        //Clone the selected items into an array
        var elements = item.parent().children('.selected').clone();
        
        //Add a property to `item` called 'multidrag` that contains the 
        //  selected items, then remove the selected items from the source list
        item.data('multidrag', elements).siblings('.selected').remove();
        
        //Now the selected items exist in memory, attached to the `item`,
        //  so we can access them later when we get to the `stop()` callback
        
        //Create the helper
        var helper = $('<li/>');
        return helper.append(elements);
    },
    stop: function (e, ui) {
        //Now we access those items that we stored in `item`s data!
        var elements = ui.item.data('multidrag');
        
        //`elements` now contains the originally selected items from the source list (the dragged items)!!
        
        //Finally I insert the selected items after the `item`, then remove the `item`, since 
        //  item is a duplicate of one of the selected items.
        ui.item.after(elements).remove();
    }

});