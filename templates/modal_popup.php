<?php
global $user?>
<div id="myModal" class="modal">
    <div class="modal-content">
        <div id="modalHeader" class="modal-header">
            <h2>Modal Header</h2>
            <div>
                <img id="draggable_icon" src="/img/drag_icon_<?=$user->getInterfaceMode()?>.png" alt="IMG">
                <span id="closeModal">&times;</span>
            </div>
        </div>
        <div class="modal-main">
            <?= showLoader(); ?>
        </div>
        <input type="hidden" id="modal_data" value="">
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
    #closeModal {
        float: right;
        font-size: 40px;
        font-weight: bold;
    }
    #closeModal:hover,
    #closeModal:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
    #draggable_icon {
        cursor: pointer;
        width: 32px;
        margin-top: 12px;
        margin-right: 20px;
    }
    .modal-header {
        padding: 16px;
    }
    .modal-body {
        padding: 2px 16px;
    }
    .modal-footer {
        padding: 16px;
    }
</style>
<script>
    $(document).ready(function () {
        $('#closeModal').on('click',closeModal)
        window.onclick = function (event) {
            if (event.target.id === 'myModal') closeModal();
        }
        $('.modal-content').draggable({
            handle: "#draggable_icon"
        });
    });

    function showModal(title, load_url, data = false) {
        $('#modalHeader h2').html(title);
        $('.modal-main').load(load_url);
        $('#myModal').show();
        if (data) $('#modal_data').val(JSON.stringify(data));
    }

    function closeModal() {
        $('#myModal').hide();
        $('.modal-main').html(showLoader());
        $('#modalHeader h2').html('Modal Header');
        $('#modal_data').val('');
    }
</script>
