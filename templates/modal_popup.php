<div id="myModal" class="modal">
    <div class="modal-content">
        <div id="modalHeader" class="modal-header">
            <h2>Modal Header</h2>
            <div>
                <span class="closeModalButton">&times;</span>
                <img src="/img/drag_icon_<?=$user->getInterfaceMode()?>.png" alt="IMG" class="draggable_icon draggable_icon_first">
                <span class="hideModalButton">-</span>
            </div>
        </div>
        <div class="modal-main">
            <?= showLoader(); ?>
        </div>
        <input type="hidden" id="modal_data" value="">
    </div>
</div>
<div id="modalHidden" class="modal-header modal-hidden">
    <div class="modal-hidden-content d-flex justify-content-between">
        <div class="modal-hidden-buttons">
            <span class="closeModalButton">×</span>
            <img src="/img/drag_icon_<?=$user->getInterfaceMode()?>.png" alt="IMG" class="draggable_icon draggable_icon_second">
            <span class="hideModalButton">-</span>
        </div>
    </div>
</div>
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 10000;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 0;
        border: 1px solid #888;
        width: 80%;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
        -webkit-animation-name: animatetop;
        -webkit-animation-duration: 0.4s;
        animation-name: animatetop;
        animation-duration: 0.4s
    }
    @-webkit-keyframes animatetop {
        from {top:-300px; opacity:0}
        to {top:0; opacity:1}
    }
    @keyframes animatetop {
        from {top:-300px; opacity:0}
        to {top:0; opacity:1}
    }
    .closeModalButton,
    .hideModalButton{
        float: right;
        font-size: 40px;
        font-weight: bold;
        padding: 5px;
    }
    .closeModalButton:hover,
    .hideModalButton:hover,
    .closeModalButton:focus,
    .hideModalButton:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
    .draggable_icon {
        cursor: pointer;
        width: 32px;
        margin-top: 17px;
        margin-right: 5px;
    }
    .modal-header {
        padding: 16px;
    }
    .modal-hidden {
        position: fixed;
        display: none;
        height: 8%;
        border: 1px solid black;
        border-radius: 15px;
        z-index: 10001;
        top: 85%;
        left: 75%;
    }
    .modal-body {
        padding: 2px 16px;
    }
    .modal-footer {
        padding: 16px;
    }
    .modal-hidden-buttons {
        position: relative;
        bottom: 15px;
    }
</style>
<script>
    $(document).ready(function () {
        $('.closeModalButton').on('click',closeModal)
        window.onclick = function (event) {
            if (event.target.id === 'myModal') closeModal();
        }
        $('.modal-content').draggable({
            handle: ".draggable_icon_first"
        });
        $('#modalHidden').draggable({
            handle: ".draggable_icon_second"
        });
        $('.hideModalButton').on('click', function (){
            $('#myModal').slideToggle();
            if ($('#modalHidden').css('display') === 'none') {
                $('#modalHidden').show();
            } else {
                $('#modalHidden').hide();
            }
        });
    });

    function showModal(title, load_url, data = false) {
        $('#modalHidden').hide();
        $('#modalHeader h2').html(title);
        $('.modal-main').load(load_url);
        $('#myModal').show();
        if (data) $('#modal_data').val(JSON.stringify(data));
    }

    function closeModal() {
        $('#modalHidden').hide();
        $('#myModal').hide();
        $('.modal-main').html(showLoader());
        $('#modalHeader h2').html('Modal Header');
        $('#modal_data').val('');
    }
</script>
