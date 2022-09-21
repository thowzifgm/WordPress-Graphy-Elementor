window.Donut = function ( uniqueId,
                          separatedLabels,
                          point,
                          showDonut,
                          showLabel,
                          outsideChart,
                          labelOffset,
                          widthTablet,
                          widthMobile,
                          globalClass,
                          showAnimation,
                          speedAnimation){
    /** Convert HTML characters. */
    String.prototype.replaceAll = function(search, replacement) {
        const target = this;
        return target.split(search).join(replacement);
    };

    const Labels = separatedLabels;
    const Points = point;
    const StrLabels = Labels.replaceAll('&#039;', "").replaceAll('&#037;', "%");
    const str = StrLabels.split(', ');
    const arrayLabels = Array.from(str);

    let count = 0;

    function visibleDonut (target) {

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

                    const data = {
                        labels: arrayLabels,
                        series: Points
                    };

                    const options = {
                        donut: true,
                        chartPadding: 0,
                        donutWidth: parseInt(showDonut),
                        showLabel: showLabel,
                        labelDirection: outsideChart === 'true' ? 'explode' : '',
                        labelOffset: outsideChart === 'true' ? parseInt(labelOffset) : 0
                    };

                    const responsiveOptions = [
                        ['screen and (min-width: 376px) and (max-width: 425px)', { donutWidth: widthTablet }],
                        ['screen and (min-width: 321px) and (max-width: 375px)', { donutWidth: widthMobile }],
                        ['screen and (max-width: 320px)', { donutWidth: widthMobile }],
                    ];

                    const donut = new Chartist.Pie( globalClass, data, options, responsiveOptions);

                    if( showAnimation === 'yes' ){
                        donut.on('draw', function(data) {
                            if(data.type === 'slice') {
                                /** Get the total path length in order to use for dash array animation. */
                                const pathLength = data.element._node.getTotalLength();

                                /** Set a dasharray that matches the path length
                                 * as prerequisite to animate dashoffset. */
                                data.element.attr({ 'stroke-dasharray': pathLength + 'px ' + pathLength + 'px' });

                                /** Create animation definition while also assigning
                                 * an ID to the animation for later sync usage. */
                                const animationDefinition = {
                                    'stroke-dashoffset': {
                                        id: 'anim' + data.index,
                                        dur: speedAnimation,
                                        from: -pathLength + 'px',
                                        to: '0px',
                                        easing: Chartist.Svg.Easing.easeOutQuint,
                                        /** We need to use `fill: 'freeze'` otherwise our animation
                                         * will fall back to initial (not visible). */
                                        fill: 'freeze'
                                    }
                                };

                                /** If this was not the first slice, we need to time the animation so that it uses
                                 * the end sync event of the previous animation. */
                                if(data.index !== 0) {
                                    animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
                                }

                                /** We need to set an initial value before the animation starts as we are
                                 * not in guided mode which would do that for us. */
                                data.element.attr({
                                    'stroke-dashoffset': -pathLength + 'px'
                                });

                                /** We can't use guided mode as the animations need
                                 * to rely on setting begin manually. */
                                data.element.animate(animationDefinition, false);
                            }
                        });
                    }

                count++;
            }

        } else {
            count = 0;
        }
    }

    const elementDonut = document.querySelector(`.elementor-element-${uniqueId}`);

    window.addEventListener('scroll', function() {
        visibleDonut (elementDonut);
    });

    visibleDonut (elementDonut);
}