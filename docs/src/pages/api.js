import React from 'react';
import Layout from '@theme/Layout';
import useDocusaurusContext from '@docusaurus/useDocusaurusContext';
import useBaseUrl from '@docusaurus/useBaseUrl';

export default function ApiPage() {
  const { siteConfig } = useDocusaurusContext();
  const apiUrl = useBaseUrl('api/index.html');
  
  React.useEffect(() => {
    // Redirect to the API documentation
    window.location.href = apiUrl;
  }, [apiUrl]);

  return (
    <Layout
      title="API Documentation"
      description="PHP API Documentation for Meeting List Lite">
      <div
        style={{
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
          minHeight: '50vh',
          fontSize: '20px',
          flexDirection: 'column',
        }}>
        <p>Redirecting to API documentation...</p>
        <p>
          If you are not redirected automatically, 
          <a href={apiUrl} style={{ marginLeft: '5px' }}>click here</a>.
        </p>
      </div>
    </Layout>
  );
}