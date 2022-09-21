window.BarPopup = function ( uniqueId,
                             separatedLabels,
                             horizontal,
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
    let horizontalBars = 'yes' === horizontal

    setTimeout( () => {

        const bar = new Chartist.Bar(`${globalClass}`, {
            labels: arrayLabels,
            series: point
        }, {
            horizontalBars: horizontalBars,
                distributeSeries: true,
                axisX: {
                showGrid: showGridX === 'yes',
                showLabel: showLabelX === 'yes',
                offset: showLabelX === 'yes' ? 40 : 0,
            },
            axisY: {
                showGrid: showGridY === 'yes',
                showLabel: showLabelY === 'yes',
                offset: showLabelY === 'yes' ? 40 : 0,
            },
            chartPadding: 0
        });

        if( showAnimation === 'yes' ){
            bar.on('draw', function(data) {
                if(data.type === 'bar') {
                    data.element.animate({
                        y2: {
                            dur: speedAnimation,
                            from: data.y1,
                            to: data.y2
                        }
                    });
                }
            });
        }

    }, 900 );

}