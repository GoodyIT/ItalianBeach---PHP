<table id="data-table-command" class="table table-striped table-vmiddle">
            <thead>
                <tr>
                    <th data-column-id="sunshadeId" data-type="numeric" data-visible="false">sunshadeId</th>
                    <th data-column-id="id" data-type="numeric">ID</th>
                    <th data-column-id="username"><?=Yii::t('messages', 'Username')?></th>
                    <th data-column-id="address"><?=Yii::t('messages', 'Address')?></th>
                    <th data-column-id="email"><?=Yii::t('messages', 'Email')?></th>
                    <th data-column-id="phonenumber"><?=Yii::t('messages', 'Phonenumber')?></th>
                    <th data-column-id="country"><?=Yii::t('messages', 'Country')?></th>
                    <th data-column-id="arrival"><?=Yii::t('messages', 'Arrival Date')?></th>
                    <th data-column-id="checkout"><?=Yii::t('messages', 'Checkout Date')?></th>
                    <th data-column-id="sunshade"><?=Yii::t('messages', 'Sunshade')?></th>
                    <th data-column-id="paidprice"><?=Yii::t('messages', 'Paid / Total')?>&nbsp;(€)</th>
                    <th data-column-id="commands" data-formatter="commands" data-sortable="false"></th>
                </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i < count($guest); $i++) {?>
                <tr>
                    <td><?=$guest[$i]['Id']?></td>
                    <td><?=$i+1?></td>
                    <td><?=$guest[$i]['username']?></td>
                    <td><?=$guest[$i]['address']?></td>
                    <td><?=$guest[$i]['email']?></td>
                    <td><?=$guest[$i]['phonenumber']?></td>
                    <td><?=$guest[$i]['country']?></td>
                    <td><?=$guest[$i]['arrival']?></td>
                    <td><?=$guest[$i]['checkout']?></td>
                    <td><?=$guest[$i]['sunshade']?></td>
                    <td><?=$guest[$i]['paidprice']?>/<?=$guest[$i]['price']?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>