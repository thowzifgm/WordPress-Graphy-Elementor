window.Dots = function ( uniqueId,
                         globalClass,
                         separatedLabels,
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

    let count = 0;

    function visibleDots (target) {

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

                    const times = function (n) {
                        return Array.apply(null, new Array(n));
                    };

                    const data = {
                        labels: arrayLabels,
                        series: point
                    };

                    const options = {
                        showLine: false,
                        fullWidth: true,
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

                    const dots = new Chartist.Line(`.${globalClass}`, data, options);

                    // Let's put a sequence number aside so we can use it in the event callbacks
                    let seq = 0;

                    // Once the chart is fully created we reset the sequence
                    dots.on('created', function() { seq = 0; });

                    if( showAnimation === 'yes' ){
                        dots.on('draw', function(data) {
                            if(data.type === 'point') {
                                // If the drawn element is a line we do a simple opacity fade in. This could also be achieved using CSS3 animations.
                                data.element.animate({
                                    opacity: {
                                    // The delay when we like to start the animation
                                    begin: seq++ * 80,
                                    // Duration of the animation
                                    dur: speedAnimation,
                                    // The value where the animation should start
                                    from: 0,
                                    // The value where it should end
                                    to: 1
                            },
                                x1: {
                                    begin: seq++ * 80,
                                    dur: speedAnimation,
                                    from: data.x - 100,
                                    to: data.x,
                                    // You can specify an easing function name or use easing functions from Chartist.Svg.Easing directly
                                    easing: Chartist.Svg.Easing.easeOutQuart
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

    const elementDots = document.querySelector(`.elementor-element-${uniqueId}`);

    window.addEventListener('scroll', function() {
        visibleDots (elementDots);
    });

    visibleDots (elementDots);
}