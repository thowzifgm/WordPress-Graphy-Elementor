window.MultilineBar = function ( uniqueId,
                                 separatedLabels,
                                 globalClass,
                                 point,
                                 barWidth,
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

    function visibleMB (target) {

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

                    const multilineBar = new Chartist.Bar(`.${globalClass}`, {
                        labels: arrayLabels,
                        series: point
                    }, {
                        seriesBarDistance: barWidth,
                        chartPadding: 0,
                            axisX: {
                            showGrid: showGridX === 'yes',
                            showLabel: showLabelX === 'yes',
                            offset: showLabelX === 'yes' ? 30 : 0,
                        },
                        axisY: {
                            labelInterpolationFnc: function (value) {
                                return value
                            },
                            scaleMinSpace: 0,
                                showGrid: showGridY === 'yes',
                                showLabel: showLabelY === 'yes',
                                offset: showLabelY === 'yes' ? 30 : 0,
                        }
                    });

                    if( showAnimation === 'yes' ) {
                        multilineBar.on( 'draw', function ( data ) {
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

                count++;
            }

        } else {
            count = 0;
        }
    }

    const elementMB = document.querySelector( `.elementor-element-${uniqueId}` );

    window.addEventListener('scroll', function() {
        visibleMB (elementMB);
    });

    visibleMB (elementMB);
}