jQuery(document).ready(function($) {
    let value_step4 = '';
    let value_step5 = '';
    $(document).on('click', '.button-step', function() {
        var step = $(this).data('step');

        analyzer(step);
    })

    $(document).on('change', '.first-three input', function() {
        var step = $(this).parent().parent().attr('data');
        analyzer(parseInt(step) + 1);
    });

    $(document).on('change', '.fourth-question input', function() {
        value_step4 = $(this).val();
        analyzerResult(value_step4,value_step4);
    });

    $(document).on('change', '.select-question input', function() {
        value_step5 = $(this).val();
        analyzerResult(value_step4, value_step5);
    });
});

function analyzer(step = 0) {
    $('html, body').animate({
        scrollTop: $('.skinanalyzer').offset().top - 130
    }, 500);
    axios({
        method: 'POST',
        url: "/ajax/skincare",
        data: {
            step: step
        },
    }).then(res => {
        if (res.data != '') {
            $('.analyzer-intro').html('');
            if (step == 5) {
                $('.analyzer-questions').remove();
                $('.analyzer-result').html(res.data);
            } else
                $('.analyzer-questions').html(res.data);
        }
    }).catch(e => console.log(e));
}

function analyzerResult(result1, result2) {
    window.location.href = result1;
    // axios({
    //     method: 'POST',
    //     url: "/ajax/skincare-result",
    //     data: {
    //         result1: result1,
    //         result2: result2
    //     },
    // }).then(res => {
    //     if (res.data != '') {
    //         window.location.href = res.data;
    //     }
    // }).catch(e => console.log(e));
}

function startOver() {
    window.location.reload();
}
