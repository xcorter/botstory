export class LinkHelper {
    static updateCoordinates(
        linkEl: SVGElement,
        x1: number,
        x2: number,
        y1: number,
        y2: number,
    ) {
        const lineEl = <SVGElement> linkEl.querySelector('.line');

        lineEl.setAttribute('x1', x1.toString());
        lineEl.setAttribute('y1', y1.toString());

        lineEl.setAttribute('x2', x2.toString());
        lineEl.setAttribute('y2', y2.toString());

        const circle = <SVGElement> linkEl.querySelector('circle');
        circle.setAttribute('cx', ((x2-x1) / 2 + x1).toString());
        circle.setAttribute('cy', ((y2-y1) / 2 + y1).toString());

        const text = <SVGElement> linkEl.querySelector('text');
        text.setAttribute('x', ((x2-x1) / 2 + x1 - 4).toString());
        text.setAttribute('y', ((y2-y1) / 2 + y1 + 4).toString());
    }
}