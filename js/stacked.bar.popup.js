window.StackedBarPopup = function ( separatedLabels,
                                    globalClass,
                                    point,
                                    showGridX,
                                    showLabelX,
                                    showGridY,
                                    showLabelY,
                                    showAnimation,
                                    speedAnimation ){
    /** Convert HTML characters. */
    String.prototype.replaceAll = function(search, replacement) {
        const target = this;
        return target.split(search).join(replacement);
    };

    const Labels = separatedLabels;
    const StrLabels = Labels.replaceAll('&#039;', "");
    const str = StrLabels.split(', ');
    const arrayLabels = Array.from(str);

    setTimeout( () => {

        const stackedBar = new Chartist.Bar(`.${globalClass}`, {
            labels: arrayLabels,
            series: point
        }, {
            stackBars: true,
                chartPadding: 0,
                axisX: {
                showGrid: showGridX === 'yes',
                showLabel: showLabelX === 'yes',
                offset: showLabelX === 'yes' ? 40 : 0
            },
            axisY: {
                labelInterpolationFnc: function (value) {
                    return value;
                },
                showGrid: showGridY === 'yes',
                showLabel: showLabelY === 'yes',
                offset: showLabelY === 'yes' ? 40 : 0
            }
        }).on('draw', function (data) {
            if (data.type === 'bar') {
                data.element.attr({
                    style: 'stroke-width: 30px'
                });
            }
        });

        if( showAnimation === 'yes' ) {
            stackedBar.on( 'draw', function ( data ) {
                if ( data.type === 'bar' ) {
                    data.element.animate( {
                        y2: {
                            dur: speedAnimation,
                            from: data.y1,
                            to: data.y2
                        }
                    } );
                }
            } );
        }

    }, 900 );

}