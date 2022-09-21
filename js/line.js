window.Line = function ( uniqueId,
                         separatedLabels,
                         globalClass,
                         point,
                         lineSmoothing,
                         showGridX,
                         showLabelX,
                         showGridY,
                         showLabelY,
                         showAnimation,
                         axisAnimationDelay,
                         axisAnimationDuration,
                         lineAnimationDelay,
                         lineAnimationDuration ){
    /** Convert HTML characters. */
    String.prototype.replaceAll = function (search, replacement) {
        const target = this;
        return target.split(search).join(replacement);
    };

    const Labels = separatedLabels;
    const StrLabels = Labels.replaceAll('&#039;', "");
    const str = StrLabels.split(', ');
    const arrayLabels = Array.from(str);

    let count = 0;

    function visibleLine (target) {

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

                    const line = new Chartist.Line(`.${globalClass}`, {
                        labels: arrayLabels,
                        series: point
                    }, {
                        lineSmooth: Chartist.Interpolation.simple({
                            divisor: lineSmoothing,
                        fillHoles: false
                    }),
                        fullWidth: true,
                            showPoint: true,
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
                        // Let's put a sequence number aside so we can use it in the event callbacks
                        let seq = 0,
                            delays = axisAnimationDelay,
                            durations = axisAnimationDuration;

                        // Once the chart is fully created we reset the sequence
                        line.on('created', function () { seq = 0; });

                        // On each drawn element by Chartist we use the Chartist.Svg API to trigger SMIL animations
                        line.on('draw', function (data) {
                            seq++;

                            if (data.type === 'line') {
                                // If the drawn element is a line we do a simple opacity fade in. This could also be achieved using CSS3 animations.
                                data.element.animate({
                                    opacity: {
                                    // The delay when we like to start the animation
                                    begin: seq * delays + lineAnimationDelay,
                                    // Duration of the animation
                                    dur: lineAnimationDuration,
                                    // The value where the animation should start
                                    from: 0,
                                    // The value where it should end
                                    to: 1
                            }
                            });
                            } else if (data.type === 'label' && data.axis === 'x') {
                                data.element.animate({
                                    y: {
                                        begin: seq * delays,
                                        dur: durations,
                                        from: data.y + 100,
                                        to: data.y,
                                        // We can specify an easing function from Chartist.Svg.Easing
                                        easing: 'easeOutQuart'
                                    }
                                });
                            } else if (data.type === 'label' && data.axis === 'y') {
                                data.element.animate({
                                    x: {
                                        begin: seq * delays,
                                        dur: durations,
                                        from: data.x - 100,
                                        to: data.x,
                                        easing: 'easeOutQuart'
                                    }
                                });
                            } else if (data.type === 'point') {
                                data.element.animate({
                                    x1: {
                                        begin: seq * delays,
                                        dur: durations,
                                        from: data.x - 10,
                                        to: data.x,
                                        easing: 'easeOutQuart'
                                    },
                                    x2: {
                                        begin: seq * delays,
                                        dur: durations,
                                        from: data.x - 10,
                                        to: data.x,
                                        easing: 'easeOutQuart'
                                    },
                                    opacity: {
                                        begin: seq * delays,
                                        dur: durations,
                                        from: 0,
                                        to: 1,
                                        easing: 'easeOutQuart'
                                    }
                                });
                            } else if (data.type === 'grid') {
                                // Using data.axis we get x or y which we can use to construct our animation definition objects
                                const pos1Animation = {
                                    begin: seq * delays,
                                    dur: durations,
                                    from: data[data.axis.units.pos + '1'] - 30,
                                    to: data[data.axis.units.pos + '1'],
                                    easing: 'easeOutQuart'
                                };

                                const pos2Animation = {
                                    begin: seq * delays,
                                    dur: durations,
                                    from: data[data.axis.units.pos + '2'] - 100,
                                    to: data[data.axis.units.pos + '2'],
                                    easing: 'easeOutQuart'
                                };

                                const animations = {};
                                animations[data.axis.units.pos + '1'] = pos1Animation;
                                animations[data.axis.units.pos + '2'] = pos2Animation;
                                animations['opacity'] = {
                                    begin: seq * delays,
                                    dur: durations,
                                    from: 0,
                                    to: 1,
                                    easing: 'easeOutQuart'
                                };

                                data.element.animate(animations);
                            }
                        });
                    }

                count++;
            }

        } else {
            count = 0;
        }
    }

    const elementLine = document.querySelector(`.elementor-element-${uniqueId}`);

    window.addEventListener('scroll', function() {
        visibleLine (elementLine);
    });

    visibleLine (elementLine);
}