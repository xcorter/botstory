class GraphConfig {

    labelStyle: object;
    bodyStyle: object;
    portStyle: object;
    nodeConfig: object;

    constructor() {
        this.labelStyle = {
            fill: '#3498DB',
            fontSize: 18,
            fontWeight: 'bold',
            fontVariant: 'small-caps'
        };

        this.bodyStyle = {
            fill: '#2C3E50',
            rx: 5,
            ry: 5,
            strokeWidth: 2
        };

        this.portStyle = {
            groups: {
                'in': {
                    position: 'top',
                    attrs: {
                        '.port-body': {
                            fill: '#16A085'
                        }
                    }
                },
                'out': {
                    attrs: {
                        '.port-body': {
                            fill: '#E74C3C'
                        }
                    }
                }
            },
            items: [{
                group: 'in',
                attrs: {
                    text: { text: 'in' }
                }
            }]
        };

        this.nodeConfig = {
            ports: {
                groups: {
                    'in': {
                        position: 'top',
                        attrs: {
                            circle: {
                                magnet: 'passive',
                                stroke: 'white',
                                fill: '#253369',
                                r: 12
                            },
                            text: {
                                pointerEvents: 'none',
                                fontSize: 12,
                                fill: 'white'
                            }
                        },
                        label: {
                            position: {
                                name: 'left',
                                args: { x: 5 }
                            }
                        }
                    },
                    out: {
                        position: 'right',
                        attrs: {
                            'circle': {
                                magnet: true,
                                stroke: 'none',
                                fill: '#31d0c6',
                                r: 12
                            }
                        }
                    }
                },
                items: [{
                    group: 'in',
                    attrs: {
                        text: { text: 'in' }
                    }
                }]
            },

            attrs: {
                '.': {
                    magnet: false
                },
                '.body': {
                    refWidth: '100%',
                    refHeight: '100%',
                    rx: '1%',
                    ry: '2%',
                    stroke: 'none',
                    fill: '#343434'
                    // fill: {
                    //     type: 'linearGradient',
                    //     stops: [
                    //         { offset: '0%', color: '#FEB663' },
                    //         { offset: '100%', color: '#31D0C6' }
                    //     ],
                    //     // Top-to-bottom gradient.
                    //     attrs: { x1: '0%', y1: '0%', x2: '0%', y2: '100%' }
                    // }
                },
                '.btn-add-option': {
                    refX: 10,
                    refDy: -22,
                    cursor: 'pointer',
                    fill: 'white'
                },
                '.btn-remove-option': {
                    xAlignment: 10,
                    yAlignment: 13,
                    cursor: 'pointer',
                    fill: 'white'
                },
                '.options': {
                    refX: 0
                },

                // Text styling.
                text: {
                    fontFamily: 'Arial'
                },
                '.option-text': {
                    fontSize: 11,
                    fill: '#fefffd',
                    refX: 30,
                    yAlignment: 'middle'
                },
                '.question-text': {
                    fill: 'white',
                    refX: '50%',
                    refY: 15,
                    fontSize: 15,
                    textAnchor: 'middle',
                    style: {
                        textShadow: '1px 1px 0px gray'
                    }
                },

                // Options styling.
                '.option-rect': {
                    rx: 3,
                    ry: 3,
                    stroke: 'white',
                    strokeWidth: 1,
                    strokeOpacity: .5,
                    fillOpacity: .5,
                    fill: 'grey',
                    refWidth: '100%'
                }
            }

        };
    }
}

export default new GraphConfig();