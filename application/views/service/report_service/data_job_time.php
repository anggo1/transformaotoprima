
    <title>Job Time Card Report</title>

    <style>
        body {
            font-family: Arial;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
        }

        .header td {
            height: 45px;
        }

        .blue {
            background: #d8e4f2;
            font-weight: bold;
            text-align: center;
        }

        .center {
            text-align: center;
        }
    </style>

</head>

<body>

    <h2>JOB TIME CARD REPORT</h2>

    <table class="header">

        <tr>

            <td width="50%">
                <b>Mechanic Name</b><br>
            </td>

            <td>
                <b>WIP Number</b><br>
            </td>

        </tr>

    </table>

    <br>

    <i>This card must be punched together with the workshop order.</i>

    <br><br>

    <table class="table table-bordered table-hover nowrap" id="mytable" width="100%">

        <tr class="blue">

            <th width="8%">CIW/GW</th>

            <th width="35%">
                Operational Code<br>
                Description
            </th>

            <th width="5%"></th>

            <th width="18%">Time Recording</th>

            <th width="15%">Date</th>

            <th width="15%">Total Time</th>

        </tr>

        <?php foreach ($dataJob as $row): ?>

            <?php

            $start = strtotime($row->start_date);
            $end   = strtotime($row->end_date);

            $total = ($end - $start) / 3600;

            ?>

            <tr>

                <td class="center">

                    <b><?= $row->wo_no; ?></b>
                </td>

                <td>

                    <b><?= $row->operation; ?></b>

                    <br>

                    <?= $row->type_of_work; ?>

                </td>

                <td class="center">

                    S

                    <br><br>

                    E

                </td>

                <td>

                    <?= date('H:i', strtotime($row->start_date)); ?>

                    <br><br>

                    <?= date('H:i', strtotime($row->end_date)); ?>

                </td>

                <td class="center">

                    <?= date('d/m/Y', strtotime($row->start_date)); ?>
                    <br><br>
                    <?= date('d/m/Y', strtotime($row->end_date)); ?>

                </td>

                <td class="center">

                    <?php // echo number_format($total,2); 
                    ?>
                    <?= number_format($row->total_time, 2); ?>

                </td>

            </tr>

        <?php endforeach; ?>

    </table>