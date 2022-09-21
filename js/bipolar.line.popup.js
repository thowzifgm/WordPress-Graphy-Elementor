window.BipolarLinePopup = function ( separatedLabels,
                                     globalClass,
                                     point,
                                     lineSmoothing,
                                     maxLines,
                                     minLines,
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

        const bipolarLine = new Chartist.Line(`.${globalClass}`, {
            labels: arrayLabels,
            series: point
        }, {
            lineSmooth: Chartist.Interpolation.simple({
                divisor: lineSmoothing,
            fillHoles: false
        }),
            high: maxLines,
            low: minLines,
            showArea: true,
                showLine: false,
                showPoint: false,
                fullWidth: true,
                chartPadding: 0,
            axisX: {
                showGrid: showGridX === 'yes',
                showLabel: showLabelX === 'yes',
                offset: showLabelX === 'yes' ? 40 :0
            },
            axisY: {
                showGrid: showGridY === 'yes',
                showLabel: showLabelY === 'yes',
                offset: showLabelY === 'yes' ? 40 :  0
            }
        });

        if( showAnimation === 'yes' ){
            bipolarLine.on('draw', function(data) {
                if(data.type === 'line' || data.type === 'area') {
                    data.element.animate({
                        d: {
                            begin: speedAnimation * data.index,
                            dur: speedAnimation,
                            from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                            to: data.path.clone().stringify(),
                            easing: Chartist.Svg.Easing.easeOutQuint
                }
                });
                }
            });
        }

    }, 900 );

}