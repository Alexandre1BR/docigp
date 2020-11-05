<div id="tabela2">
    <style>
        .tabela_docigp2 {
            border:1px solid #C0C0C0;
            border-collapse:collapse;
            padding:5px;
            width: 100%;
        }

        .tabela_docigp2 th {
            border:1px solid #C0C0C0;
            padding:5px;
            background:#0d2954;
            color: #ffffff;
        }
        .tabela_docigp2 td {
            border:1px solid #C0C0C0;
            padding:5px;
            text-align: center;
        }
    </style>

    <table class="tabela_docigp2">
        <tr>
            <th>VALOR ANUAL RECEBIDO</th>
            <th>VALOR ANUAL DESPESAS</th>
            <th>VALOR ANUAL DEVOLUÇÕES</th>
            <th>SITUAÇÃO</th>
        </tr>
        <tr>
            <td rowspan="2">{{$totalsTable['creditTotal']}}</td>
            <td rowspan="1">{{$totalsTable['spentTotal']}}</td>
            <td rowspan="1">{{$totalsTable['refundTotal']}}</td>
            <td rowspan="2">{{$totalsTable['situation']}}</td>
        </tr>
        <tr>
            <td colspan="2">{{$totalsTable['spentAndRefundTotal']}}</td>
        </tr>
    </table>
</div>
