window.BipolarBarPopup = function ( separatedLabels,
                                    point,
                                    globalClass,
                                    scale,
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
    const StrLabels = Labels.replaceAll('&#039;', "'");
    const str = StrLabels.split(', ');
    const arrayLabels = Array.from(str);

    setTimeout( () => {

        const data = {
            labels: arrayLabels,
            series: [ point ]
        };

        const options = {
            fullWidth: true,
            high: scale,
            low: -scale,
            chartPadding: 0,
                axisX: {
                showGrid: showGridX === 'yes',
                showLabel: showLabelX === 'yes',
                offset: showLabelX === 'yes' ? 40 : 0,
            },
            axisY: {
                showGrid: showGridY === 'yes',
                showLabel: showLabelY === 'yes',
                offset: showLabelY === 'yes' ? 40 : 0,
            }
        };

        const bipolarBar = new Chartist.Bar(`${globalClass}`, data, options);

        if( showAnimation === 'yes' ) {
            bipolarBar.on( 'draw', function ( data ) {
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