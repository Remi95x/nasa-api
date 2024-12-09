<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>NASA API</title>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">NASA API</h1>
        <form class="my-4">
            <div class="mb-3">
                <label for="start_date" class="form-label">Fecha Inicio</label>
                <input type="date" id="start_date" class="form-control">
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">Fecha Fin</label>
                <input type="date" id="end_date" class="form-control">
            </div>
            <button type="button" id="fetch-data" class="btn btn-primary">Buscar</button>
        </form>
        <div id="resultados" class="mt-5">

        </div>
    </div>
    <script>
        window.fetchDataUrl = "{{ route('fetch.data') }}";
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('#fetch-data').addEventListener('click', () => {
                const startDate = document.querySelector('#start_date').value;
                const endDate = document.querySelector('#end_date').value;

                if (!startDate || !endDate) {
                    alert('Por favor, selecciona ambas fechas.');
                    return;
                }

                console.log("URL de Fetch:", window.fetchDataUrl);

                fetch(window.fetchDataUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            start_date: startDate,
                            end_date: endDate
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            console.error("Error en la respuesta:", response);
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Datos recibidos:', data);
                        if (data.error) {
                            alert(data.error);
                        } else if (data.message) {
                            alert(data.message);
                        } else {
                            mostrarDatos(data);
                        }
                    })
                    .catch(error => {
                        console.error('Error en la solicitud:', error);
                        alert(error.message || 'Ocurrió un error al procesar tu solicitud.');
                    });
            });

            function mostrarDatos(data) {
                const resultados = document.querySelector('#resultados');
                resultados.innerHTML = '';

                if (!data || !data.data || data.data.length === 0) {
                    resultados.innerHTML = '<p>No hay datos disponibles para el rango seleccionado.</p>';
                    return;
                }

                const instrumentPercentages = data.instrumentPercentages;

                data.data.forEach(evento => {
                    const card = document.createElement('div');
                    card.classList.add('card', 'mb-3', 'p-3', 'bg-light');

                    let instrumentos = '';
                    if (evento.instruments && evento.instruments.length > 0) {
                        instrumentos = evento.instruments.map(inst => {
                            const displayName = inst.displayName || 'Sin Nombre';
                            const percentage = instrumentPercentages[displayName] || 0;
                            return `<li>${displayName} (${percentage}%)</li>`;
                        }).join('');
                    } else {
                        instrumentos = '<li>Sin Datos</li>';
                    }

                    let linkedEventsInfo = '';
                    if (evento.linkedEvents && evento.linkedEvents.length > 0) {
                        linkedEventsInfo = evento.linkedEvents.map(link => {
                            if (link.activityID) {
                                return `<p><strong>activityID:</strong> ${link.activityID}</p>`;
                            } else {
                                return '<p>Sin evento relacionado</p>';
                            }
                        }).join('');
                    } else {
                        linkedEventsInfo = '<p>Sin eventos relacionados</p>';
                    }

                    card.innerHTML = `
                        <h4>${evento.activityID || 'Sin ID de actividad'}</h4>
                        <p><strong>Fecha de inicio:</strong> ${evento.startTime || 'Sin Datos'}</p>
                        <p><strong>Instrumentos usados:</strong></p>
                        <ul>${instrumentos}</ul>
                        <p><strong>Submission Time:</strong> ${evento.submissionTime || 'Sin Datos'}</p>
                        <p><strong>Link:</strong> <a href="${evento.link || '#'}" target="_blank">${evento.link || 'Sin Datos'}</a></p>
                        <p><strong>Version:</strong> ${evento.versionId || 'Sin Datos'}</p>
                        <p><strong>Linked Events:</strong></p>
                        <div>${linkedEventsInfo}</div>
                    `;

                    resultados.appendChild(card);
                });
            }


        });
    </script>
</body>

</html>