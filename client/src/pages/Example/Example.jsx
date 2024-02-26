// This component is an example on how to structure a page.

import ExampleComponent from '../../components/ExampleComponent/ExampleComponent';
import { API_URL } from '../../consts';

// All styles should come from a module.css file. Each page should have it's own style declarations
import styles from './Example.module.css';

export default function Example() {

    // To fetch the /example route on the server
    fetch(`${API_URL}/example`);

    // The body of a page follows the same rules as the component does.

    return (
        <div>
            <h1 className={ styles['title'] }>Chez Gustave</h1>
            <ExampleComponent />
        </div>
    );
}