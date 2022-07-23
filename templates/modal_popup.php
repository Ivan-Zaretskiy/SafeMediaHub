<div id="myModal" class="modal">
    <div class="modal-content">
        <div id="modalHeader" class="modal-header">
            <h2>Modal Header</h2>
            <span id="closeModal">&times;</span>
        </div>
        <div class="modal-main">
            <?= showLoader(); ?>
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
    #closeModal {
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    #closeModal:hover,
    #closeModal:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
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
            if (event.target.id === 'myModal') {
                closeModal();
            }
        }
    });

    function showModal(title, load_url) {
        let modal = $('#myModal');
        let modalMain = $('.modal-main');

        $('#modalHeader h2').html(title);
        modalMain.load(load_url);

        modal.show();
    }

    function closeModal() {
        $('#myModal').hide();
        $('.modal-main').html(showLoader());
        $('#modalHeader h2').html('Modal Header');
    }
</script>
