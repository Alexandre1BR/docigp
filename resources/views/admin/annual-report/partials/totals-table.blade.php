<table>
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
