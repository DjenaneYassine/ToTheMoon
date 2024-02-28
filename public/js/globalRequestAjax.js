function star(){
    for (var i = 0; i < 100; i++) {
        var star =
            '<div class="star m-0" style="animation: twinkle ' +
            (Math.random() * 5 + 5) +
            's linear ' +
            (Math.random() * 1 + 1) +
            's infinite; top: ' +
            Math.random() * $(window).height() +
            'px; left: ' +
            Math.random() * $(window).width() +
            'px;"></div>';
            $('.homescreen').append(star);
    }
}



function uptadeData(data){
    let TTES = $(".TTES");
    let CLP = $(".CLP");
    TTES.empty();
    CLP.empty();
    data.forEach(function (item){
            let ss = item.ss.split('-');
            let home = item.home.name;
            let away = item.away.name;
            let set = item.set.split('-');
        if(item.league.name === "Czech Liga Pro"){
            let newRowCLP =
                "<div class='flex justify-between mb-4'>"+
                    "<div>"+
                        "<span class='bg-red-100 text-red-800 text-xs font-medium me-1 px-1 py-0 rounded dark:bg-red-600 dark:text-white'>En direct</span>"+
                        "<div class='text-gray-300 text-sm font-semibold'>" + home + "<br>" + away + "</div>"+
                    "</div>"+
                    "<div class='text-white flex align-bottom items-end'>"+
                        "<div class='font-semibold text-sm text-gray-300'>" + ss[0] + "<br>" + ss[1] + "</div>"+
                        "<div class='mx-2'></div>"+
                        "<div class='font-semibold text-sm text-yellow-300'>" + set[0] + "<br>" + set[1] + "</div>"+
                    "</div>"+
                "</div>"

            CLP.append(newRowCLP);
        }
        if(item.league.name === "TT Elite Series"){
            let newRowTTES =
                "<div class='flex justify-between mb-4'>"+
                    "<div>"+
                        "<span class='bg-red-100 mb-2 text-red-800 text-xs font-medium me-1 px-1 py-0 rounded dark:bg-red-600 dark:text-white'>En direct</span>"+
                        "<div class='text-gray-300 text-sm font-semibold'>" + home + "<br>" + away + "</div>"+
                    "</div>"+
                    "<div class='text-white flex align-bottom items-end'>"+
                        "<div class='font-semibold text-sm text-gray-300'>" + ss[0] + "<br>" + ss[1] + "</div>"+
                        "<div class='mx-2'></div>"+
                        "<div class='font-semibold text-sm text-yellow-300'>" + set[0] + "<br>" + set[1] + "</div>"+
                    "</div>"+
                "</div>"
            TTES.append(newRowTTES);
        }
    })
}

function fetchDataAndUptade(){
        $.ajax({
            url: "/api",
            method: 'GET',
            dataType: 'json',
            success: function (response){
                    uptadeData(response.results);
            },
            error: function(error) {
                console.error(error);
            }
        })

}


