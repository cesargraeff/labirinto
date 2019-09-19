(function(){
    'use strict';

    const api = 'http://localhost/labirinto/backend/index.php';
    
    $('#loading').hide();

    function send(){
        $('#labirinto').hide();
        $('#historico').hide();
        $('#loading').show();
        const data = $('#form').serialize();
        $.get(api+'?'+data, (res) => {
            montaTabuleiro(res['tabuleiro']);
            montaHistorico(res['historico']);
            $('#labirinto').show();
            $('#historico').show();
            $('#loading').hide();
        }, 'json');
    }


    function montaTabuleiro(res){
        let html = '';
        for(let i=0; i<10; i++){
            html += '<tr>';
            for(let j=0; j<10; j++){
                const value = res[i][j];
                let classes = '';
                classes += !value[0] ? 'bu ' : '';
                classes += !value[1] ? 'bd ' : '';
                classes += !value[2] ? 'bl ' : '';
                classes += !value[3] ? 'br ' : '';
                classes += value[4] ? 'active ' : '';
                html += '<td class="'+classes+'"></td>';
            }
            html += '</tr>';
        }

        $('#labirinto table').html(html);
    }


    function montaHistorico(historico){
        let html = '';
        historico.forEach(val => {
            html += `
                <tr>
                    <td>${val.geracao}</td>
                    <td>${val.genes}</td>
                    <td>${val.aptidao}</td>
                </tr>
            `;
        });
        $('#historico tbody').html(html);
    }

    $(document).ready(function(){

        $('#form').submit((evt) => {
            evt.preventDefault();
            send();
        });

    });

})();