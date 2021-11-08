<?php

    $filename = "website_data_" . date('Ymd') . ".xls";
  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");
?>
<table>
    <tr>
        <td>
            dddd
        </td>
        <td>
            123
        </td>
        <td>
            USD
        </td>
    </tr>
</table>