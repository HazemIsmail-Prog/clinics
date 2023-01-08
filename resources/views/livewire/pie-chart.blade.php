<div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div
            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary w-50">Revenue Sources</h6>
            <div wire:loading>
                <h6 class="m-0 font-weight-bold text-primary"><div class="spinner-border small"></div></h6>
            </div>

            <select wire:model="filter" class="form-control custom-select w-50">
                <option value="users">Users</option>
                <option value="doctors">Doctors</option>
                <option value="departments">Departments</option>
            </select>

        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="chart-pie pt-4 pb-2">
                <canvas id="myPieChart"></canvas>
            </div>
            <div class="mt-4 text-center small">
                @foreach($pie_chart as $row)
                    <div class="row">
                        <div class="col-6">
                            <div class="text-left">
                                <i class="fas fa-circle" style="color: {{$row['color']}}"></i> {{$row['labels']}}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-right" style="color: {{$row['color']}}">
                                {{number_format($row['data'],3)}} KWD
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


@push('scripts')

    <script>

        document.addEventListener('livewire:load', function () {
            resources = {!! json_encode($pie_chart) !!};
            loadChart(resources);
        });

        window.addEventListener('update_chart_source', event => {
            resources = event.detail.resources;
            loadChart(resources);
        });

        function loadChart(resources) {


            // var get_data = resources;
            var labels = [];
            var data = [];
            var colors = [];
            resources.forEach(element => labels.push(element['labels']));
            resources.forEach(element => data.push(element['data']));
            resources.forEach(element => colors.push(element['color']));


            // Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#858796';

            // Pie Chart Example
            var ctx = document.getElementById("myPieChart");
            var myPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    // labels: ["Direct", "Referral", "Social"],
                    labels: labels,
                    datasets: [{
                        // data: [55, 30, 15],
                        data: data,
                        backgroundColor: colors,
                        // hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 80,
                },
            });

        }

    </script>

@endpush
