console.log(options.url)

$('#search').select2({
    placeholder: 'Поиск',
    width: '100%',
    ajax: {
        url: options.url + '/articles/search',
        dataType: 'json',
        delay: 250,
        data: (params) => {
            return {query: params.term};
        },
        processResults: (data) => {
            return {results: data.data};
        },
        cache: true
    },
    minimumInputLength: 3,
    templateResult: (data) => data.title,
    templateSelection: selection
});

function selection(data) {
    if (data.id) {
        window.location = options.url + '/articles/' + data.id;
    }
}