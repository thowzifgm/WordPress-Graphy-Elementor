window.BipolarLine = function ( uniqueId,
                                separatedLabels,
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

    let count = 0;

    function visibleBL (target) {

        const targetPosition = {
                top: window.pageYOffset + target.getBoundingClientRect().top,
                left: window.pageXOffset + target.getBoundingClientRect().left,
                right: window.pageXOffset + target.getBoundingClientRect().right,
                bottom: window.pageYOffset + target.getBoundingClientRect().bottom
            },

            windowPosition = {
                top: window.pageYOffset,
                left: window.pageXOffset,
                right: window.pageXOffset + document.documentElement.clientWidth,
                bottom: window.pageYOffset + document.documentElement.clientHeight
            };

        if (targetPosition.bottom > windowPosition.top &&
            targetPosition.top < windowPosition.bottom &&
            targetPosition.right > windowPosition.left &&
            targetPosition.left < windowPosition.right) {

            if( count === 0 ) {

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
                        offset: showLabelX === 'yes' ? 40 : 0
                        },
                    axisY: {
                        showGrid: showGridY === 'yes',
                        showLabel: showLabelY === 'yes',
                        offset: showLabelY === 'yes' ? 40 : 0
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

                count++;
            }

        } else {
            count = 0;
        }
    }

    const elementBL = document.querySelector(`.elementor-element-${uniqueId}`);

    window.addEventListener('scroll', function() {
        visibleBL (elementBL);
    });

    visibleBL (elementBL);
}