var $commission = $('#members-table');

function setChildren() {
    i=0;
    var com = $('.is-com');
    com.each(function(index){
        i++;
        if(index>0 && com.data('parent') == com.eq(index-1).data('id-cat')){
            com.attr('data-order', com.eq(index-1).data('data-order') + com.data('sub-order'));
        } else {

        }
    });
}


// $commission.find('li.is-com').sort(function(a,b){
//     return +a.getAttribute('data-parent') - +b.getAttribute('data-parent');
// }).appendTo($commission);
//
// $subcommission.find('li.is-com').sort(function(c,d){
//     return +c.getAttribute('data-suborder') - +d.getAttribute('data-suborder');
// }).appendTo($subcommission);


$commission.find('li.is-com').sort(function(a,b){
    return +a.getAttribute('data-order') - +b.getAttribute('data-order');
}).appendTo($commission);
