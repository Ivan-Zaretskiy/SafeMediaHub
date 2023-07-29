$(document).ajaxStart(function(){
    showLoadingBar();
});
$(document).ajaxStop(function(){
    hideLoadingBar();
});
function showLoadingBar() {
    var loadingHTML = '<div class="loading-progress">\n' +
        '    <div class="loading-bar">\n' +
        '        <span class="bar-animation"></span>\n' +
        '    </div>\n' +
        '</div>';
    $('body').prepend(loadingHTML);
}
function hideLoadingBar() {
    $('.loading-progress').remove();
}
function showAlert(text, type){
    let title = '';
    switch (type) {
        case 'success': title = 'Success';
            break;
        case 'error': title = 'Something goes wrong';
            break;
        default: title = 'Warning';
            break;
    }
    Swal.fire(
        title,
        text,
        type
    )
}
function copyToClipboard(elementId) {
    var aux = document.createElement("input");
    aux.setAttribute("value", document.getElementById(elementId).innerHTML);
    document.body.appendChild(aux);
    aux.select();
    document.execCommand("copy");
    document.body.removeChild(aux);
}

function blockCUI(text = '<p class="m-0">Please wait...</p>'){
    $.blockUI({ css: {
            padding: '10px',
            border: '',
            left: '40%',
            'color': '#000',
            'border-radius': '20px',
            width: '20%',
            backgroundColor: '#fafafa',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: 1,
            'font-size': '20px'
        },message:text });
}

function unblockCUI(){
    $.unblockUI();
}

function seasonAction(id, elem ='#season_', minus = true, to_first = false) {
    var current_value = parseInt($(elem+id)[0].innerText);
    if (isNaN(current_value)) current_value = 0;
    var new_value = false;
    if (minus) {
        if(current_value > 1) {
            new_value = current_value - 1;
        } else {
            showAlert('Can\'t be less that 0');
        }
    } else {
        if(to_first) {
            new_value = 1;
        } else {
            new_value = current_value + 1;
        }
    }
    if (new_value) {
        blockCUI();
        $.ajax({
            method: "POST",
            url: '/load.php?page=serials&action=seasonAction',
            data: {'id': id, 'season': new_value},
            success: function (data) {
                let response = JSON.parse(data);
                if (response.success === true) {
                    if (typeof ($('#season_'+id)[0]) !== 'undefined') $('#season_'+id)[0].innerText = response.new_value;
                    if (typeof ($('#season_info_'+id)[0]) !== 'undefined') $('#season_info_'+id)[0].innerText = response.new_value;
                    unblockCUI();
                } else {
                    unblockCUI();
                    showAlert('Try Later!', 'error');
                }
            }
        });
    }
}

function episodeAction(id, elem ='#episode_', minus = true, to_first = false) {
    var current_value = parseInt($(elem+id)[0].innerText);
    if (isNaN(current_value)) current_value = 0;
    var new_value = false;
    if (minus) {
        if(current_value > 0) {
            new_value = current_value - 1;
        } else {
            showAlert('Can\'t be less that 0');
        }
    } else {
        if(to_first) {
            new_value = 1;
        } else {
            new_value = current_value + 1;
        }
    }
    if (new_value) {
        blockCUI();
        $.ajax({
            method: "POST",
            url: '/load.php?page=serials&action=episodeAction',
            data: {'id': id, 'episode': new_value},
            success: function (data) {
                let response = JSON.parse(data);
                if (response.success === true) {
                    unblockCUI();
                    if (typeof ($('#episode_'+id)[0]) !== 'undefined') $('#episode_'+id)[0].innerText = response.new_value;
                    if (typeof ($('#episode_info_'+id)[0]) !== 'undefined') $('#episode_info_'+id)[0].innerText = response.new_value;
                } else {
                    unblockCUI();
                    showAlert('Try Later!', 'error');
                }
            }
        });
    }
}
function showLoader() {
    return '<div class="loader"></div alt="LOADER">';
}

function load_page(index, elem){
    var span_page_id = $(elem).attr('page-id');
    var span_href = $(elem).attr('ajax-href');
    var page = $('#'+span_page_id);
    localStorage.setItem('last_page', index);
    $('.li-pass').removeClass('active-pass');
    $(elem).closest('.li-pass').addClass('active-pass');
    $('.page-content').removeClass('active-page');
    page.addClass('active-page');
    if (page.attr('data-ready') === 'false'){
        page.load(span_href);
    }
    page.attr('data-ready','true');
}

function toggleMenuAdmin(with_change = true) {
    var toggleAdmin = document.querySelector('.toggle-pass');
    var navigationAdmin = document.querySelector('.navigation-pass');
    var mainAdmin = document.querySelector('.main-pass');
    if (with_change) {
        if (+window.localStorage.getItem("preferHidden")) {
            window.localStorage.setItem("preferHidden", 0);
        } else {
            window.localStorage.setItem("preferHidden", 1);
        }
    }
    toggleAdmin.classList.toggle('active');
    navigationAdmin.classList.toggle('active');
    mainAdmin.classList.toggle('active');
}

function checkPin(func, id = false) {
    var data = {};
    data.func = func;
    if (id) data.id = id;
    showModal('Enter your first PIN for: '+func,'/load.php?page=keysManager&action=checkPin&ajax=true', data);
}

function check2Pins(func, id) {
    var data = {};
    data.func = func;
    if (id) data.id = id;
    showModal('Enter your PINs for: '+func,'/load.php?page=keysManager&action=check2Pins&ajax=true', data);
}

function getModalData(){
    return JSON.parse($('#modal_data').val());
}

function downloadText(filename, text) {
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + JSON.parse(encodeURIComponent(text)));
    element.setAttribute('download', filename);

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
}

function updateAllTables() {
    if (typeof(imagesTable) !== 'undefined') imagesTable.ajax.reload();
    if (typeof(fieldsTable) !== 'undefined') fieldsTable.ajax.reload();
}

function textAreaAdjust(element) {
    element.style.height = "1px";
    element.style.height = (25 + element.scrollHeight) + "px";
}
