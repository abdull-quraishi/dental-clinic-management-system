<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<title>Patient History</title>

<style>

body{
    font-family: Arial, sans-serif;
    margin:20px;
    color:#222;
}

.header{
    text-align:center;
    border-bottom:2px solid #000;
    margin-bottom:20px;
    padding-bottom:10px;
}

.section{
    margin-top:25px;
}

.section-title{
    background:#f3f4f6;
    padding:10px;
    font-weight:bold;
    border:1px solid #ddd;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

table th,
table td{
    border:1px solid #ddd;
    padding:8px;
    text-align:left;
}

table th{
    background:#f8f8f8;
}

</style>
</head>

<body onload="window.print()">

<div class="header">

    <h1>Dental Clinic</h1>

    <h3>Patient Consultation History</h3>

</div>


<table>
    <tr>
        <th>Patient Name</th>
        <td>
            {{ $patient->first_name }}
            {{ $patient->last_name }}
        </td>

        <th>Email</th>
        <td>{{ $patient->email }}</td>
    </tr>

    <tr>
        <th>Phone</th>
        <td>{{ $patient->phone_number }}</td>

        <th>Gender</th>
        <td>{{ $patient->gender }}</td>
    </tr>
</table>

<div class="section">

    <div class="section-title">
        Appointment History
    </div>

    <table>

        <tr>
            <th>Date</th>
            <th>Doctor</th>
            <th>Service</th>
            <th>Status</th>
        </tr>

        @foreach($appointments as $appointment)

        <tr>
            <td>
                {{ $appointment->appointment_date }}
            </td>

            <td>
                Dr.
                {{ $appointment->doctor->first_name ?? '' }}
                {{ $appointment->doctor->last_name ?? '' }}
            </td>

            <td>
                {{ $appointment->service_type }}
            </td>

            <td>
                {{ $appointment->status }}
            </td>
        </tr>

        @endforeach

    </table>

</div>

<div class="section">

    <div class="section-title">
        Diagnosis History
    </div>

    <table>

        <tr>
            <th>Date</th>
            <th>Doctor</th>
            <th>Diagnosis</th>
            <th>Status</th>
        </tr>

        @foreach($treatments as $treatment)

        <tr>

            <td>{{ $treatment->treatment_date }}</td>

            <td>
                Dr.
                {{ $treatment->doctor->first_name ?? '' }}
                {{ $treatment->doctor->last_name ?? '' }}
            </td>

            <td>{{ $treatment->diagnosis }}</td>

            <td>{{ $treatment->treatment_status }}</td>

        </tr>

        @endforeach

    </table>

</div>

<div class="section">

    <div class="section-title">
        Prescription & Billing History
    </div>

    <table>

        <tr>
            <th>Date</th>
            <th>Service</th>
            <th>Medicines</th>
            <th>Total Bill</th>
            <th>Status</th>
        </tr>

        @foreach($prescriptions as $prescription)

        <tr>

            <td>
                {{ $prescription->prescription_date }}
            </td>

            <td>
                {{ $prescription->service->name ?? 'General Checkup' }}
            </td>

            <td>

                @foreach($prescription->prescriptionItems as $item)

                    {{ $item->medicine->name ?? '' }}
                    ({{ $item->quantity }})
                    <br>

                @endforeach

            </td>

            <td>
                $
                {{ number_format($prescription->billing->total_amount ?? 0,2) }}
            </td>

            <td>
                {{ ucfirst($prescription->billing->status ?? 'unpaid') }}
            </td>

        </tr>

        @endforeach

    </table>

</div>

</body>
</html>
