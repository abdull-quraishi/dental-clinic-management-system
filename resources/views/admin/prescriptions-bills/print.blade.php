<!DOCTYPE html>
<html>
<head>
    <title>Prescription & Bill</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            margin:30px;
        }

        .header{
            text-align:center;
            margin-bottom:20px;
        }

        .header h2{
            margin-top:10px;
        }

        .section{
            margin-top:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        table,
        th,
        td{
            border:1px solid #000;
        }

        th,
        td{
            padding:8px;
        }

        .total{
            text-align:right;
            margin-top:20px;
            font-size:18px;
            font-weight:bold;
        }

        @media print{

            .print-btn{
                display:none;
            }

        }

    </style>

</head>
<body>

<div class="header">

  <div class="">
    <img src="{{ asset('images/clinic-logo/logo.png') }}" alt="Clinic Logo" style="border-radius:50%; width:100px;height:100px; object-fit:contain;  ">
  </div>
    <h2 style="margin-bottom:10px;">
        Quraishi Dental Clinic
    </h2>

    <h4 style="margin:0;">
         Prescription & Bill Report
    </h4>

</div>

<button
    onclick="window.print()"
    class="print-btn">
    Print
</button>

<hr>

<div class="section">

    <h3>Patient Information</h3>

    <p>
        <strong>Name:</strong>
        {{ $prescription->patient->first_name }}
        {{ $prescription->patient->last_name }}
    </p>

    <p>
        <strong>Doctor:</strong>
        Dr.
        {{ $prescription->doctor->first_name }}
        {{ $prescription->doctor->last_name }}
    </p>

    <p>
        <strong>Date:</strong>
        {{ $prescription->prescription_date }}
    </p>

</div>

<div class="section">

    <h3>Prescription Details</h3>

    <p>
        <strong>Service:</strong>
        {{ $prescription->service->name ?? 'N/A' }}
    </p>

    <p>
        <strong>Instructions:</strong>
        {{ $prescription->instructions }}
    </p>

    <table>

        <thead>

        <tr>
            <th>Medicine</th>
            <th>Dosage</th>
            <th>Price</th>
        </tr>

        </thead>

        <tbody>

        @foreach($prescription->prescriptionItems as $item)

            <tr>

                <td>
                    {{ $item->medicine->name ?? '' }}
                </td>

                <td>
                    {{ $item->medicine->dosage ?? '' }} mg
                </td>

                <td>
                    ${{ $item->medicine->price ?? 0 }}
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

</div>

<div class="section">

    <h3>Billing Details</h3>

    <table>

        <tr>
            <td>Appointment Fee</td>
            <td>${{ $prescription->billing->appointment_fee ?? 0 }}</td>
        </tr>

        <tr>
            <td>Service Total</td>
            <td>${{ $prescription->billing->service_total ?? 0 }}</td>
        </tr>

        <tr>
            <td>Medicine Total</td>
            <td>${{ $prescription->billing->medicine_total ?? 0 }}</td>
        </tr>

        <tr>
            <td>Status</td>
            <td>{{ ucfirst($prescription->billing->status ?? '-') }}</td>
        </tr>

    </table>

    <div class="total">

        Total Amount:
        ${{ $prescription->billing->total_amount ?? 0 }}

    </div>

</div>

<br><br>

<div style="margin-top:40px;">

    ______________________

    <br>

    Doctor Signature

</div>

<script>

window.onload=function(){

    window.print();

}

</script>

</body>
</html>
